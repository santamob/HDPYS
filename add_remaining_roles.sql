-- SQL Script: Eksik Rolleri Veritabanına Ekleme
-- Tarih: 2026-02-10
-- Açıklama: RoleConsts.cs'te tanımlı 7 rolden eksik olanları AspNetRoles tablosuna ekler

USE [PYS]
GO

PRINT 'Eksik roller kontrol ediliyor ve ekleniyor...'
GO

-- 1. Kullanıcı rolü
IF NOT EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = 'Kullanıcı')
BEGIN
    INSERT INTO AspNetRoles (Id, Name, NormalizedName, ConcurrencyStamp)
    VALUES ('A5D3B6F8-1C2E-4D3F-9A8B-7C6E5D4F3A2B', 'Kullanıcı', 'KULLANICI', NEWID())
    PRINT '✓ Kullanıcı rolü eklendi'
END
ELSE
    PRINT '○ Kullanıcı rolü zaten mevcut'
GO

-- 2. Kademelendirme Yöneticisi rolü
IF NOT EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = 'Kademelendirme Yöneticisi')
BEGIN
    INSERT INTO AspNetRoles (Id, Name, NormalizedName, ConcurrencyStamp)
    VALUES ('B7E4C9D2-3F5A-4E6B-8C9D-2A3B4C5D6E7F', 'Kademelendirme Yöneticisi', 'KADEMELENDIRME YONETICISI', NEWID())
    PRINT '✓ Kademelendirme Yöneticisi rolü eklendi'
END
ELSE
    PRINT '○ Kademelendirme Yöneticisi rolü zaten mevcut'
GO

-- 3. Director rolü
IF NOT EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = 'Director')
BEGIN
    INSERT INTO AspNetRoles (Id, Name, NormalizedName, ConcurrencyStamp)
    VALUES ('C8F5D3A1-4B6C-5D7E-9F8A-3B4C5D6E7F8A', 'Director', 'DIRECTOR', NEWID())
    PRINT '✓ Director rolü eklendi'
END
ELSE
    PRINT '○ Director rolü zaten mevcut'
GO

-- 4. Manager rolü
IF NOT EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = 'Manager')
BEGIN
    INSERT INTO AspNetRoles (Id, Name, NormalizedName, ConcurrencyStamp)
    VALUES ('D9A6E4B2-5C7D-6E8F-1A9B-4C5D6E7F8A9B', 'Manager', 'MANAGER', NEWID())
    PRINT '✓ Manager rolü eklendi'
END
ELSE
    PRINT '○ Manager rolü zaten mevcut'
GO

-- 5. Executive rolü
IF NOT EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = 'Executive')
BEGIN
    INSERT INTO AspNetRoles (Id, Name, NormalizedName, ConcurrencyStamp)
    VALUES ('E1B7F5C3-6D8E-7F9A-2B1C-5D6E7F8A9B1C', 'Executive', 'EXECUTIVE', NEWID())
    PRINT '✓ Executive rolü eklendi'
END
ELSE
    PRINT '○ Executive rolü zaten mevcut'
GO

-- 6. Staff rolü
IF NOT EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = 'Staff')
BEGIN
    INSERT INTO AspNetRoles (Id, Name, NormalizedName, ConcurrencyStamp)
    VALUES ('F2C8A6D4-7E9F-8A1B-3C2D-6E7F8A9B1C2D', 'Staff', 'STAFF', NEWID())
    PRINT '✓ Staff rolü eklendi'
END
ELSE
    PRINT '○ Staff rolü zaten mevcut'
GO

-- Sonuç kontrolü
PRINT ''
PRINT 'Mevcut Roller:'
SELECT Name, NormalizedName, Id FROM AspNetRoles ORDER BY Name
GO

PRINT ''
PRINT 'İşlem tamamlandı!'
GO
