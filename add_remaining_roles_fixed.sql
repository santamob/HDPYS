-- SQL Script: Eksik Rolleri Veritabanına Ekleme (Düzeltilmiş)
-- Tarih: 2026-02-10

SET NOCOUNT ON;
SET QUOTED_IDENTIFIER ON;
GO

USE [PYS]
GO

PRINT 'Eksik roller kontrol ediliyor ve ekleniyor...'
PRINT ''

-- 1. Kullanıcı rolü - NormalizedName düzeltme
IF EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = N'Kullanıcı' AND NormalizedName <> N'KULLANICI')
BEGIN
    UPDATE AspNetRoles
    SET NormalizedName = N'KULLANICI'
    WHERE Name = N'Kullanıcı'
    PRINT '✓ Kullanıcı rolü normalized name düzeltildi'
END
ELSE IF NOT EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = N'Kullanıcı')
BEGIN
    INSERT INTO AspNetRoles (Id, Name, NormalizedName, ConcurrencyStamp)
    VALUES ('A5D3B6F8-1C2E-4D3F-9A8B-7C6E5D4F3A2B', N'Kullanıcı', N'KULLANICI', NEWID())
    PRINT '✓ Kullanıcı rolü eklendi'
END
ELSE
    PRINT '○ Kullanıcı rolü zaten mevcut'

-- 2. Kademelendirme Yöneticisi rolü - NormalizedName düzeltme
IF EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = N'Kademelendirme Yöneticisi' AND NormalizedName <> N'KADEMELENDIRME YONETICISI')
BEGIN
    UPDATE AspNetRoles
    SET NormalizedName = N'KADEMELENDIRME YONETICISI'
    WHERE Name = N'Kademelendirme Yöneticisi'
    PRINT '✓ Kademelendirme Yöneticisi rolü normalized name düzeltildi'
END
ELSE IF NOT EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = N'Kademelendirme Yöneticisi')
BEGIN
    INSERT INTO AspNetRoles (Id, Name, NormalizedName, ConcurrencyStamp)
    VALUES ('B7E4C9D2-3F5A-4E6B-8C9D-2A3B4C5D6E7F', N'Kademelendirme Yöneticisi', N'KADEMELENDIRME YONETICISI', NEWID())
    PRINT '✓ Kademelendirme Yöneticisi rolü eklendi'
END
ELSE
    PRINT '○ Kademelendirme Yöneticisi rolü zaten mevcut'

-- 3. Director rolü
IF NOT EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = N'Director')
BEGIN
    INSERT INTO AspNetRoles (Id, Name, NormalizedName, ConcurrencyStamp)
    VALUES ('C8F5D3A1-4B6C-5D7E-9F8A-3B4C5D6E7F8A', N'Director', N'DIRECTOR', NEWID())
    PRINT '✓ Director rolü eklendi'
END
ELSE
    PRINT '○ Director rolü zaten mevcut'

-- 4. Manager rolü
IF NOT EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = N'Manager')
BEGIN
    INSERT INTO AspNetRoles (Id, Name, NormalizedName, ConcurrencyStamp)
    VALUES ('D9A6E4B2-5C7D-6E8F-1A9B-4C5D6E7F8A9B', N'Manager', N'MANAGER', NEWID())
    PRINT '✓ Manager rolü eklendi'
END
ELSE
    PRINT '○ Manager rolü zaten mevcut'

-- 5. Executive rolü
IF NOT EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = N'Executive')
BEGIN
    INSERT INTO AspNetRoles (Id, Name, NormalizedName, ConcurrencyStamp)
    VALUES ('E1B7F5C3-6D8E-7F9A-2B1C-5D6E7F8A9B1C', N'Executive', N'EXECUTIVE', NEWID())
    PRINT '✓ Executive rolü eklendi'
END
ELSE
    PRINT '○ Executive rolü zaten mevcut'

-- 6. Staff rolü
IF NOT EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = N'Staff')
BEGIN
    INSERT INTO AspNetRoles (Id, Name, NormalizedName, ConcurrencyStamp)
    VALUES ('F2C8A6D4-7E9F-8A1B-3C2D-6E7F8A9B1C2D', N'Staff', N'STAFF', NEWID())
    PRINT '✓ Staff rolü eklendi'
END
ELSE
    PRINT '○ Staff rolü zaten mevcut'

PRINT ''
PRINT 'Mevcut Roller:'
PRINT '----------------------------------------'
SELECT Name, NormalizedName, Id FROM AspNetRoles ORDER BY Name

PRINT ''
PRINT '✅ İşlem tamamlandı!'
GO
