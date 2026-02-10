SET NOCOUNT ON;
SET QUOTED_IDENTIFIER ON;
GO

USE [PYS]
GO

PRINT 'Eksik roller kontrol ediliyor...'
PRINT ''

-- Kademelendirme Yöneticisi rolü - mevcut ID ile kontrol et, yoksa ekle
IF NOT EXISTS (SELECT 1 FROM AspNetRoles WHERE Id = '55929733-870B-4895-B62B-B9249FA86A04')
BEGIN
    INSERT INTO AspNetRoles (Id, Name, NormalizedName, ConcurrencyStamp)
    VALUES ('55929733-870B-4895-B62B-B9249FA86A04', N'Kademelendirme Yöneticisi', N'KADEMELENDIRME YONETICISI', NEWID())
    PRINT '✓ Kademelendirme Yöneticisi rolü eklendi (ID: 55929733-...A04)'
END
ELSE
BEGIN
    -- Normalized name düzeltme
    UPDATE AspNetRoles
    SET NormalizedName = N'KADEMELENDIRME YONETICISI'
    WHERE Id = '55929733-870B-4895-B62B-B9249FA86A04'
    AND NormalizedName <> N'KADEMELENDIRME YONETICISI'
    PRINT '○ Kademelendirme Yöneticisi rolü zaten mevcut (normalized name kontrol edildi)'
END

-- Staff rolü - yeni ID ile ekle
IF NOT EXISTS (SELECT 1 FROM AspNetRoles WHERE Name = N'Staff')
BEGIN
    INSERT INTO AspNetRoles (Id, Name, NormalizedName, ConcurrencyStamp)
    VALUES ('F2C8A6D4-7E9F-8A1B-3C2D-6E7F8A9B1C2D', N'Staff', N'STAFF', NEWID())
    PRINT '✓ Staff rolü eklendi'
END
ELSE
    PRINT '○ Staff rolü zaten mevcut'

PRINT ''
PRINT 'Tüm Roller (7 adet bekleniyor):'
PRINT '========================================='
SELECT
    ROW_NUMBER() OVER (ORDER BY Name) as [#],
    Name as [Rol Adı],
    NormalizedName as [Normalized],
    Id as [GUID]
FROM AspNetRoles
ORDER BY Name

PRINT ''
PRINT 'Toplam Rol Sayısı:'
SELECT COUNT(*) as [Sayı] FROM AspNetRoles

PRINT ''
PRINT '✅ İşlem tamamlandı!'
GO
