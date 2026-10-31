@echo off
REM Avvia install.ps1 senza dover cambiare le policy di PowerShell.
REM Uso: install.bat mio-shop
set TARGET=%1
if "%TARGET%"=="" set TARGET=shop
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0install.ps1" -Target "%TARGET%"
pause
