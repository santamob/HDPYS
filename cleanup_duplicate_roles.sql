-- SQL Script: Duplicate Rolleri Temizleme
-- Tarih: 2026-02-10
-- Açıklama: Yanlış normalized name'li eski rolleri siler ve kullanıcıları yeni rollere taşır

SET NOCOUNT ON;
SET QUOTED_IDENTIFIER ON;
GO

USE [PYS]
GO

PRINT 'Duplicate roller temizleniyor...'
PRINT ''

-- Eski "Kullanıcı" rolüne ait kullanıcıları yeni role taşı
DECLARE @OldKullaniciId UNIQUEIDENTIFIER = '55929733-870B-4895-B62B-B9249FA86A05'
DECLARE @NewKullaniciId UNIQUEIDENTIFIER = 'A5D3B6F8-1C2E-4D3F-9A8B-7C6E5D4F3A2B'

IF EXISTS (SELECT 1 FROM AspNetRoles WHERE Id = @OldKullaniciId)
BEGIN
    -- Kullanıcı rol ilişkilerini yeni role taşı
    UPDATE AspNetUserRoles
    SET RoleId = @NewKullaniciId
    WHERE RoleId = @OldKullaniciId
    AND NOT EXISTS (
        SELECT 1 FROM AspNetUserRoles ur2
        WHERE ur2.UserId = AspNetUserRoles.UserId
        AND ur2.RoleId = @NewKullaniciId
    )

    -- Eski rolü sil
    DELETE FROM AspNetRoles WHERE Id = @OldKullaniciId
    PRINT '✓ Eski "Kullanıcı" rolü temizlendi'
END

-- Eski "Kademelendirme Yöneticisi" rolüne ait kullanıcıları yeni role taşı
DECLARE @OldKademelendirmeId UNIQUEIDENTIFIER = '55929733-870B-4895-B62B-B9249FA86A04'
DECLARE @NewKademelendirmeId UNIQUEIDENTIFIER = 'B7E4C9D2-3F5A-4E6B-8C9D-2A3B4C5D6E7F'

IF EXISTS (SELECT 1 FROM AspNetRoles WHERE Id = @OldKademelendirmeId)
BEGIN
    -- Kullanıcı rol ilişkilerini yeni role taşı
    UPDATE AspNetUserRoles
    SET RoleId = @NewKademelendirmeId
    WHERE RoleId = @OldKademelendirmeId
    AND NOT EXISTS (
        SELECT 1 FROM AspNetUserRoles ur2
        WHERE ur2.UserId = AspNetUserRoles.UserId
        AND ur2.RoleId = @NewKademelendirmeId
    )

    -- Eski rolü sil
    DELETE FROM AspNetRoles WHERE Id = @OldKademelendirmeId
    PRINT '✓ Eski "Kademelendirme Yöneticisi" rolü temizlendi'
END

PRINT ''
PRINT 'Güncel Roller (7 adet):'
PRINT '----------------------------------------'
SELECT
    ROW_NUMBER() OVER (ORDER BY Name) as [#],
    Name as [Rol Adı],
    NormalizedName as [Normalized],
    Id as [GUID]
FROM AspNetRoles
ORDER BY Name

PRINT ''
SELECT COUNT(*) as [Toplam Rol Sayısı] FROM AspNetRoles

PRINT ''
PRINT '✅ Temizleme işlemi tamamlandı!'
GO
