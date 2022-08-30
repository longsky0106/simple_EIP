<?php
$sql_ly_bom = "SELECT
	登入時間
	, 帳號
	, 使用者
	, [單據/功能操作] = case when [單據/功能操作] !='' then [單據/功能操作] else '' end
	, 電腦名稱
	, IP
	, '已連線時間' = REPLACE(已連線時間,' ','')
	, '閒置時間' = REPLACE(閒置時間,' ','')
	FROM (

	SELECT
	Finall.系統類別, Finall.登入日期, Finall.登入時間, Finall.電腦名稱, Finall.帳號, Finall.使用者, Finall.已連線時間, Finall.閒置時間, Finall.執行指令1, Finall.執行指令2
	, IP = case when Finall.IP = '<named pipe>' then '無法取得IP ' + Finall.IP
		   else Finall.IP
		   end
	, [單據/功能操作] = case when WORKFILE.[單據/功能操作] IS NULL then '' 
						else WORKFILE.[單據/功能操作] 
						end

	FROM
	(
		SELECT 
		pctly.blocked,
		'系統類別' =	case when SOFTKIND=11 then '生產製造' 
						else 
							case when SOFTKIND=7 then '出口貿易' 
							else 
								case when SOFTKIND=20 then '會計財務' 
								else '其他' 
								end
							end
						end,
		spid, 
		pctly.[工作站號(凌越)],
		pctly.登入日期, pctly.登入時間, pctly.電腦名稱, pctly.帳號, pctly.使用者, pctly.已連線時間, pctly.閒置時間,pctly.IP
		,pctly.執行指令1,pctly.執行指令2 
		FROM 
		(
			SELECT DISTINCT 
			執行指令1
			, 執行指令2
			, SOFTKIND
			, MDS.spid
			, MDS.kpid
			, MDS.blocked
			,'登入日期' = case  when MDS.login_time is NULL then 
							CONVERT(varchar(100), WORKDATE, 111) 
						  else 
							CONVERT(varchar(100), MDS.login_time, 111) 
						  end
			,'登入時間' = case  when MDS.login_time is NULL then 
							CONVERT(varchar(100), WORKDATE, 8) 
						  else 
							CONVERT(varchar(100), MDS.login_time, 8) 
						  end
			--, MDS.last_batch'最後回應時間'
			, '電腦名稱' = '(Web 版) ' + WORKNAME collate chinese_taiwan_stroke_ci_as
			, XMKEYDBLY50.dbo.SQLNLOC.WORKNUM'工作站號(凌越)'
			--, WORKNAME'主機名稱(凌越)'
			, program_name'程式名稱'
			, MDS.hostprocess'主機程序'
			, Ipuserno'帳號'
			, Ipusernm'使用者'
			,'已連線時間'=	case  when MDS.login_time is NULL then 
								'* 已斷線' 
							else
								case  when (STR(convert(decimal(8,1),DATEDIFF(mi,MDS.login_time,GETDATE()))))>60 then 
									STR(convert(decimal(8,1),DATEDIFF(mi,MDS.login_time,GETDATE())/60))+'小時'
									+CONVERT(varchar(50), STR(convert(decimal(8,1),DATEDIFF(mi,MDS.login_time,GETDATE())))
									-STR(convert(decimal(8,1),DATEDIFF(mi,MDS.login_time,GETDATE())/60))*60)+'分鐘'
								else
									STR(convert(decimal(8,1),DATEDIFF(mi,MDS.login_time,GETDATE())))+'分鐘' 
								end
							end
			--,'已連線時間'=case  when MDS.login_time is NULL then '異常斷線' else STR(convert(decimal(8,1),ROUND(DATEDIFF(mi,MDS.login_time,GETDATE())/60.0,1)))+'小時' end
			, '閒置時間'=	case  when LTM.last_batch is NULL then 
								'*無法取得' 
							else
								STR(DATEDIFF(mi,LTM.last_batch,GETDATE()))+'分鐘'
							end
			, 'IP' = PCinfo.IP + ' (RDP連線來源)'
			, FILENUM, LOCKKIND, KEYVALUE, FUNNO, M_INDEX, PW_USER, MENUNM, STAYTM, BATCHNO
			--, ROW_NUMBER ( ) OVER ( PARTITION BY WORKNAME order by MDS.spid DESC) as rn 
			FROM XMKEYDBLY50.dbo.SQLNLOC RIGHT JOIN
				(
					SELECT DISTINCT
					spid, kpid, blocked
					, login_time
					, last_batch
					, hostname
					, hostprocess
					,'程序ID'=STR(a.spid, 4)
					, '工作站名稱'=CONVERT(CHAR(20), a.hostname)
					, '帳號' = upper(SUBSTRING(b.text, CHARINDEX('ca_receive=''', b.text) + LEN('ca_receive='''), 4))
					,'SQL_SOFTKIND' =	case when program_name = 'LyStk' then 11 
						else 
							case when program_name = 'LyTrade' then 7 
							else 
								case when program_name = 'LyAct' then 20 
								else '' 
								end
							end
						end
					FROM master..sysprocesses a CROSS APPLY
					sys.dm_exec_sql_text(a.sql_handle) b
					WHERE a.blocked IN ( SELECT blocked
					FROM master..sysprocesses )
					AND program_name LIKE 'Ly%'
					AND program_name !='LyService'
					AND program_name !='LyPrevview'
					AND hostname ='DBSRV'
					--AND b.text LIKE '%ca_receive=''%'
				)AS MDS
				on Ipuserno = 帳號 collate chinese_taiwan_stroke_ci_as
				LEFT JOIN
				(
					SELECT 
					'HostName' = REPLACE(SUBSTRING([HostName(IP)], 1, CHARINDEX(' (', [HostName(IP)]) - 1), ' ', '') collate chinese_taiwan_stroke_ci_as
					, 'IP' = REPLACE(
								SUBSTRING([HostName(IP)], CHARINDEX(' (', [HostName(IP)]) + 2 , LEN([HostName(IP)]) - CHARINDEX(' (', [HostName(IP)])), 
							')', '' ) collate chinese_taiwan_stroke_ci_as
					FROM PCT.dbo.MS_RDP_TSListUsers
				)AS PCinfo on WORKNAME = PCinfo.HostName collate chinese_taiwan_stroke_ci_as
				LEFT JOIN (
					SELECT DISTINCT 
					hostprocess
					, last_batch
					FROM master..sysprocesses a CROSS APPLY
					sys.dm_exec_sql_text(a.sql_handle) b
					WHERE a.blocked IN ( SELECT blocked
					FROM master..sysprocesses )
					AND program_name LIKE 'Ly%'
					AND b.text NOT LIKE '%ca_receive=''%'
					--AND program_name = 'LyStk'
					AND hostprocess collate chinese_taiwan_stroke_ci_as IN (
						SELECT DISTINCT hostprocess
						FROM master..sysprocesses a CROSS APPLY
						sys.dm_exec_sql_text(a.sql_handle) b
						WHERE a.blocked IN ( SELECT blocked
						FROM master..sysprocesses )
						AND program_name LIKE 'Ly%'
						AND b.text LIKE '%ca_receive=''%'
						--AND program_name = 'LyStk'
					)
				)AS LTM on MDS.hostprocess = LTM.hostprocess
				LEFT JOIN XMLY5000.DBO.SQLNCTRL 
				on XMKEYDBLY50.dbo.SQLNLOC.WORKNUM = XMLY5000.DBO.SQLNCTRL.WORKNUM
				LEFT JOIN sys.dm_exec_connections on spid = session_id 
				LEFT JOIN
				(
					SELECT DISTINCT
					hostprocess
					,'程序ID'=STR(a.spid, 4)
					, '工作站名稱'=CONVERT(CHAR(20), a.hostname)
					, '執行指令2'= b.text
					, '帳號' = upper(SUBSTRING(b.text, CHARINDEX('ca_receive=''', b.text) + 12, 4))
					,'SQL_SOFTKIND' =	case when program_name = 'LyStk' then 11 
						else 
							case when program_name = 'LyTrade' then 7 
							else 
								case when program_name = 'LyAct' then 20 
								else '' 
								end
							end
						end
					FROM master..sysprocesses a CROSS APPLY
					sys.dm_exec_sql_text(a.sql_handle) b
					WHERE a.blocked IN ( SELECT blocked
					FROM master..sysprocesses )
					AND program_name like 'Ly%'
					AND CONVERT(CHAR(16), DB_NAME(a.dbid)) = 'XMKEYDBLY50'
					AND b.text like '%ca_receive%'
					-- and blocked <> 0
					--ORDER BY STR(spid, 4)
				)AS MMS on MDS.hostprocess = MMS.hostprocess collate chinese_taiwan_stroke_ci_as
				LEFT JOIN
				(
					SELECT DISTINCT
					hostprocess
					,'程序ID'=STR(a.spid, 4)
					, '工作站名稱'=CONVERT(CHAR(20), a.hostname)
					, '執行指令1'= b.text
					,'SQL_SOFTKIND' =	case when program_name = 'LyStk' then 11 
						else 
							case when program_name = 'LyTrade' then 7 
							else 
								case when program_name = 'LyAct' then 20 
								else '' 
								end
							end
						end
					FROM master..sysprocesses a CROSS APPLY
					sys.dm_exec_sql_text(a.sql_handle) b
					WHERE a.blocked IN ( SELECT blocked
					FROM master..sysprocesses )
					AND program_name like 'Ly%'
					AND CONVERT(CHAR(16), DB_NAME(a.dbid)) = 'XMLY5000'
					--ORDER BY 工作站名稱
				)AS MMS2 on MMS.hostprocess = MMS2.hostprocess collate chinese_taiwan_stroke_ci_as
				LEFT JOIN
				(
					SELECT * From master.dbo.sysprocesses MDS 
					WHERE dbid = 5 --AND loginame = 'LYSXM' 
					AND program_name LIKE 'Ly%'
				)AS MDS3
				on MDS.hostprocess = MDS3.hostprocess collate chinese_taiwan_stroke_ci_as 
			
			WHERE WORKNAME != '' 
			--AND　WORKNAME NOT LIKE 'DBSRV%'  
			--AND SOFTKIND = 7  --出口貿易
			--AND SOFTKIND = 11 --生產製造
			--AND SOFTKIND = 20 --會計財務
		)AS pctly
		
		UNION ALL
		SELECT 
		pctly.blocked,
		'系統類別' =	case when SOFTKIND=11 then '生產製造' 
						else 
							case when SOFTKIND=7 then '出口貿易' 
							else 
								case when SOFTKIND=20 then '會計財務' 
								else '其他' 
								end
							end
						end,
		spid,
		pctly.[工作站號(凌越)],
		pctly.登入日期, pctly.登入時間, pctly.電腦名稱, pctly.帳號, pctly.使用者, pctly.已連線時間, pctly.閒置時間,pctly.IP
		,pctly.執行指令1,pctly.執行指令2 
		FROM 
		(
			SELECT DISTINCT 
			執行指令1
			,執行指令2
			,SOFTKIND
			,spid
			, kpid
			, blocked
			,'登入日期' = case  when login_time is NULL then 
							CONVERT(varchar(100), WORKDATE, 111) 
						  else 
							CONVERT(varchar(100), login_time, 111) 
						  end
			,'登入時間' = case  when login_time is NULL then 
							CONVERT(varchar(100), WORKDATE, 8) 
						  else 
							CONVERT(varchar(100), login_time, 8) 
						  end
			--,last_batch'最後回應時間'
			, '電腦名稱' = case  when hostname is NULL then 
							WORKNAME collate chinese_taiwan_stroke_ci_as
						  else 
							hostname collate chinese_taiwan_stroke_ci_as
						  end
			, XMKEYDBLY50.dbo.SQLNLOC.WORKNUM'工作站號(凌越)'
			--, WORKNAME'主機名稱(凌越)'
			--, program_name'程式名稱'
			--, hostprocess'主機程序'
			, Ipuserno'帳號'
			, Ipusernm'使用者'
			,'已連線時間'=	case  when login_time is NULL then 
								'* 已斷線' 
							else
								case  when (STR(convert(decimal(8,1),DATEDIFF(mi,login_time,GETDATE()))))>60 then 
									STR(convert(decimal(8,1),DATEDIFF(mi,login_time,GETDATE())/60))+'小時'
									+CONVERT(varchar(50), STR(convert(decimal(8,1),DATEDIFF(mi,login_time,GETDATE())))
									-STR(convert(decimal(8,1),DATEDIFF(mi,login_time,GETDATE())/60))*60)+'分鐘'
								else
									STR(convert(decimal(8,1),DATEDIFF(mi,login_time,GETDATE())))+'分鐘' 
								end
							end
			--,'已連線時間'=case  when login_time is NULL then '異常斷線' else STR(convert(decimal(8,1)
			--,ROUND(DATEDIFF(mi,login_time,GETDATE())/60.0,1)))+'小時' end
			--, '閒置時間'=	case  when last_batch is NULL then 
			--					'*無法取得' 
			--				else
			--					STR(DATEDIFF(mi,last_batch,GETDATE()))+'分鐘'
			--				end
			,'閒置時間'=	case  when last_batch is NULL then 
								'*無法取得' 
							else
								case  when (STR(DATEDIFF(mi,last_batch,GETDATE())))>60 then 
									STR(DATEDIFF(mi,last_batch,GETDATE())/60)+'小時'
									+CONVERT(varchar(50), STR(DATEDIFF(mi,last_batch,GETDATE()))
									-STR(DATEDIFF(mi,last_batch,GETDATE())/60)*60)+'分鐘'
								else
									STR(DATEDIFF(mi,last_batch,GETDATE()))+'分鐘'
								end
							end
			,'IP' = case  when client_net_address is NULL then 
								'*無法取得' 
							else
								client_net_address
							end
			, FILENUM, LOCKKIND, KEYVALUE, FUNNO, M_INDEX, PW_USER, MENUNM, STAYTM, BATCHNO 
			, ROW_NUMBER ( ) OVER ( PARTITION BY hostname,SOFTKIND,Ipuserno order by spid DESC) as rn 
			FROM XMKEYDBLY50.dbo.SQLNLOC LEFT JOIN
				(
					SELECT * 
					,'SQL_SOFTKIND' =	case when program_name = 'LyStk' then 11 
						else 
							case when program_name = 'LyTrade' then 7 
							else 
								case when program_name = 'LyAct' then 20 
								else '' 
								end
							end
						end
					From master.dbo.sysprocesses MDS 
					WHERE dbid = 5 --AND loginame = 'LYSXM' 
					AND program_name LIKE 'Ly%'
					AND program_name !='LyService'
					AND program_name !='LyPrevview'
				)AS MDS
				on WORKNAME = hostname  collate chinese_taiwan_stroke_ci_as and SQL_SOFTKIND = SOFTKIND
				LEFT JOIN XMLY5000.DBO.SQLNCTRL 
				on XMKEYDBLY50.dbo.SQLNLOC.WORKNUM = XMLY5000.DBO.SQLNCTRL.WORKNUM and SQL_SOFTKIND = XMLY5000.DBO.SQLNCTRL.WORKNUM
				LEFT JOIN sys.dm_exec_connections on spid = session_id 
				LEFT JOIN
				(
					SELECT DISTINCT
					'程序ID'=STR(a.spid, 4)
					, '工作站名稱'=CONVERT(CHAR(20), a.hostname)
					, '執行指令2'= b.text
					, '帳號' = SUBSTRING(b.text, CHARINDEX('ca_receive=''', b.text) + 12, 4)
					,'SQL_SOFTKIND' =	case when program_name = 'LyStk' then 11 
						else 
							case when program_name = 'LyTrade' then 7 
							else 
								case when program_name = 'LyAct' then 20 
								else '' 
								end
							end
						end
					FROM master..sysprocesses a CROSS APPLY
					sys.dm_exec_sql_text(a.sql_handle) b
					WHERE a.blocked IN ( SELECT blocked
					FROM master..sysprocesses )
					AND program_name like 'Ly%'
					AND CONVERT(CHAR(16), DB_NAME(a.dbid)) = 'XMKEYDBLY50'
					AND b.text like '%ca_receive%'
					-- and blocked <> 0
					--ORDER BY STR(spid, 4)
				)AS MMS on WORKNAME = MMS.工作站名稱 collate chinese_taiwan_stroke_ci_as  and MMS.SQL_SOFTKIND = SOFTKIND
				LEFT JOIN
				(
					SELECT DISTINCT
					'程序ID'=STR(a.spid, 4)
					, '工作站名稱'=CONVERT(CHAR(20), a.hostname)
					, '執行指令1'= b.text
					,'SQL_SOFTKIND' =	case when program_name = 'LyStk' then 11 
						else 
							case when program_name = 'LyTrade' then 7 
							else 
								case when program_name = 'LyAct' then 20 
								else '' 
								end
							end
						end
					FROM master..sysprocesses a CROSS APPLY
					sys.dm_exec_sql_text(a.sql_handle) b
					WHERE a.blocked IN ( SELECT blocked
					FROM master..sysprocesses )
					AND program_name like 'Ly%'
					AND CONVERT(CHAR(16), DB_NAME(a.dbid)) = 'XMLY5000'
					-- and blocked <> 0
					--ORDER BY STR(spid, 4)
				)AS MMS2 on WORKNAME = MMS2.工作站名稱 collate chinese_taiwan_stroke_ci_as   and MMS2.SQL_SOFTKIND = SOFTKIND
			WHERE WORKNAME != '' 
			AND　WORKNAME NOT LIKE 'DBSRV%'
			AND WORKNAME NOT IN
			(
				--遠端連線列表
				SELECT 
				'HostName' = REPLACE(SUBSTRING([HostName(IP)], 1, CHARINDEX(' (', [HostName(IP)]) - 1), ' ', '') collate chinese_taiwan_stroke_ci_as
				--,*
				FROM PCT.dbo.MS_RDP_TSListUsers
				LEFT JOIN [PCT].[dbo].[LY_TASK] ON REPLACE([RDP-Port],' ','')  = SS_Name
				WHERE IM_NAME IS NOT NULL
			)
		)AS pctly

		WHERE rn != ''
		AND rn = 1

	)AS Finall
	LEFT JOIN (
	SELECT WORKNUM,'單據/功能操作' = case when m.[單據/功能操作] != '' then LEFT(m.[單據/功能操作],LEN(m.[單據/功能操作])-1) else '' end
		FROM (
			SELECT DISTINCT
			WORKNUM, 
			'單據/功能操作' = (
				SELECT M_xNAME + '-' + 
					case when CONVERT(NVARCHAR(MAX),LOCKKIND) = 1 then '新增'
									else 
										case when CONVERT(NVARCHAR(MAX),LOCKKIND) = 2 then '修改'
										else CONVERT(NVARCHAR(MAX),LOCKKIND)
										end
									end
				+ '[' + KEYVALUE + ']' + ' ,  '
				FROM XMLY5000.DBO.SQLNCTRL AS F1
				LEFT JOIN (
					Select
						substring(M_NAME,4,len(M_NAME)) AS M_xNAME
					, FILENUM
					, FUNNO
					, M_KIND
					From XMLY5000.[dbo].MenuBar 
					WHERE M_INDEX !=''
					AND M_KIND != 'NoUse'
					GROUP BY M_INDEX,substring(M_NAME,4,len(M_NAME)),FILENUM,FUNNO,M_KIND/*,PW_USER*/
				) AS F2
				on F1.FILENUM = F2.FILENUM AND F1.FUNNO = F2.FUNNO
				WHERE F3.WORKNUM = WORKNUM  /****************/
				FOR XML PATH('')
			)
			FROM XMLY5000.DBO.SQLNCTRL AS F3
		)M 
	)AS WORKFILE ON WORKFILE.WORKNUM = Finall.[工作站號(凌越)]

	)AS FFinal
	WHERE FFinal.系統類別 != ''
	AND FFinal.系統類別 = :sys_type
	ORDER BY FFinal.系統類別, FFinal.登入時間
	";	
?>