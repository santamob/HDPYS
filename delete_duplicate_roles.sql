SET NOCOUNT ON;
SET QUOTED_IDENTIFIER ON;
GO

USE [PYS]
GO

PRINT 'Duplicate roller siliniyor...'
PRINT ''

-- Yeni oluşturulan Kademelendirme Yöneticisi rolünü sil
DELETE FROM AspNetRoles WHERE Id = 'B7E4C9D2-3F5A-4E6B-8C9D-2A3B4C5D6E7F'
PRINT '✓ Duplicate Kademelendirme Yöneticisi rolü silindi (ID: B7E4C9D2-...)'

-- Yeni oluşturulan Staff (yanlışlıkla oluşturulmuş) rolünü sil
DELETE FROM AspNetRoles WHERE Id = 'F2C8A6D4-7E9F-8A1B-3C2D-6E7F8A9B1C2D'
PRINT '✓ Yanlış Staff rolü silindi (ID: F2C8A6D4-...)'

PRINT ''
PRINT 'Güncel Roller:'
PRINT '----------------------------------------'
SELECT
    ROW_NUMBER() OVER (ORDER BY Name) as [#],
    Name as [Rol Adı],
    NormalizedName,
    Id
FROM AspNetRoles
ORDER BY Name

PRINT ''
SELECT COUNT(*) as [Toplam Rol Sayısı] FROM AspNetRoles

PRINT ''
PRINT '✅ İşlem tamamlandı!'
GO
