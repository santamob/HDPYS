SET NOCOUNT ON;
SET QUOTED_IDENTIFIER ON;
GO

USE [PYS]
GO

PRINT 'Kademelendirme Yöneticisi rolü Name alanı düzeltiliyor...'
PRINT ''

-- Kademelendirme Yöneticisi rolünün Name alanını düzelt
UPDATE AspNetRoles
SET Name = N'Kademelendirme Yöneticisi'
WHERE Id = '55929733-870B-4895-B62B-B9249FA86A04'

PRINT '✓ Name alanı düzeltildi: Kademelendirme Yöneticisi'
PRINT ''

-- Kontrol
PRINT 'Güncel Kademelendirme Yöneticisi rolü:'
PRINT '========================================='
SELECT
    Name as [Rol Adı],
    NormalizedName as [Normalized],
    Id as [GUID]
FROM AspNetRoles
WHERE Id = '55929733-870B-4895-B62B-B9249FA86A04'

PRINT ''
PRINT '✅ İşlem tamamlandı!'
GO
