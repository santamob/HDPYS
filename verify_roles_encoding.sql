SET NOCOUNT ON;
GO

USE [PYS]
GO

PRINT 'ROL DOĞRULAMA RAPORU'
PRINT '================================================='
PRINT ''

-- Tüm rolleri göster
PRINT 'Veritabanındaki Roller:'
PRINT '-------------------------------------------------'

DECLARE @RoleCount INT = 0

-- Admin
IF EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = 'Admin' COLLATE Turkish_CI_AS)
BEGIN
    SET @RoleCount = @RoleCount + 1
    PRINT '✓ 1. Admin'
END

-- Director
IF EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = 'Director' COLLATE Turkish_CI_AS)
BEGIN
    SET @RoleCount = @RoleCount + 1
    PRINT '✓ 2. Director'
END

-- Executive
IF EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = 'Executive' COLLATE Turkish_CI_AS)
BEGIN
    SET @RoleCount = @RoleCount + 1
    PRINT '✓ 3. Executive'
END

-- Kademelendirme Yöneticisi (Türkçe karakter kontrolü)
IF EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = N'Kademelendirme Yöneticisi' COLLATE Turkish_CI_AS)
BEGIN
    SET @RoleCount = @RoleCount + 1
    PRINT '✓ 4. Kademelendirme Yöneticisi (Türkçe karakterler: ö)'
END
ELSE
    PRINT '✗ 4. Kademelendirme Yöneticisi - BULUNAMADI!'

-- Kullanıcı (Türkçe karakter kontrolü)
IF EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = N'Kullanıcı' COLLATE Turkish_CI_AS)
BEGIN
    SET @RoleCount = @RoleCount + 1
    PRINT '✓ 5. Kullanıcı (Türkçe karakterler: ı)'
END
ELSE
    PRINT '✗ 5. Kullanıcı - BULUNAMADI!'

-- Manager
IF EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = 'Manager' COLLATE Turkish_CI_AS)
BEGIN
    SET @RoleCount = @RoleCount + 1
    PRINT '✓ 6. Manager'
END

-- Staff
IF EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = 'Staff' COLLATE Turkish_CI_AS)
BEGIN
    SET @RoleCount = @RoleCount + 1
    PRINT '✓ 7. Staff'
END

PRINT ''
PRINT '-------------------------------------------------'
PRINT 'Toplam: ' + CAST(@RoleCount AS VARCHAR) + ' / 7 rol bulundu'

IF @RoleCount = 7
    PRINT '✅ TÜM ROLLER DOĞRU!'
ELSE
    PRINT '⚠️ EKSIK ROL VAR!'

PRINT ''
PRINT 'Detaylı Bilgi:'
SELECT
    Name as [Rol_Adi],
    LEN(Name) as [Uzunluk],
    NormalizedName as [Normalized],
    Id
FROM AspNetRoles
ORDER BY Name

GO
