
@echo off

dir ..\images /s/b | find "meta" > all_files.txt
FOR /F "usebackq delims==" %%i IN (all_files.txt) DO @echo "%%i" && del "%%i" /q

dir ..\images /ad /s/b | find "meta" > all_files.txt
FOR /F "usebackq delims==" %%i IN (all_files.txt) DO @echo "%%i" && rmdir "%%i" /q


dir ..\images /s/b | find "thumb" > all_files.txt
FOR /F "usebackq delims==" %%i IN (all_files.txt) DO @echo "%%i" && del "%%i" /q

dir ..\images /ad /s/b | find "thumb" > all_files.txt
FOR /F "usebackq delims==" %%i IN (all_files.txt) DO @echo "%%i" && rmdir "%%i" /q


