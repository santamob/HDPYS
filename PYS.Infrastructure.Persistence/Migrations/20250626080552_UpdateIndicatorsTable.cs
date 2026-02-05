using System;
using Microsoft.EntityFrameworkCore.Migrations;

#nullable disable

namespace PYS.Infrastructure.Persistence.Migrations
{
    /// <inheritdoc />
    public partial class UpdateIndicatorsTable : Migration
    {
        /// <inheritdoc />
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.AddColumn<string>(
                name: "IndicatorPeriodText",
                table: "Indicators",
                type: "nvarchar(max)",
                nullable: false,
                defaultValue: "");

            migrationBuilder.UpdateData(
                table: "AspNetRoles",
                keyColumn: "Id",
                keyValue: new Guid("55929733-870b-4895-b62b-b9249fa86a06"),
                column: "ConcurrencyStamp",
                value: "03a140ee-6b1e-4ae4-acb2-209e42908a9f");

            migrationBuilder.UpdateData(
                table: "AspNetUsers",
                keyColumn: "Id",
                keyValue: new Guid("037ffd4d-e033-4220-b044-c4c6eddad883"),
                columns: new[] { "ConcurrencyStamp", "CreatedDate", "PasswordHash", "SecurityStamp" },
                values: new object[] { "698eaf2e-30f4-473f-9bd2-715f44d33546", new DateTime(2025, 6, 26, 11, 5, 51, 567, DateTimeKind.Local).AddTicks(3402), "AQAAAAIAAYagAAAAEKBftT0BUetafgfwsV5yjl2Xh4nIZhxE77hRF3G5q62Ss8GYZdnaWUlWFFxBEubJYw==", "9ba21b67-5f65-4761-a59e-32efdc3edb00" });
        }

        /// <inheritdoc />
        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DropColumn(
                name: "IndicatorPeriodText",
                table: "Indicators");

            migrationBuilder.UpdateData(
                table: "AspNetRoles",
                keyColumn: "Id",
                keyValue: new Guid("55929733-870b-4895-b62b-b9249fa86a06"),
                column: "ConcurrencyStamp",
                value: "841aa070-4d8f-493d-a9b3-039bf50e5055");

            migrationBuilder.UpdateData(
                table: "AspNetUsers",
                keyColumn: "Id",
                keyValue: new Guid("037ffd4d-e033-4220-b044-c4c6eddad883"),
                columns: new[] { "ConcurrencyStamp", "CreatedDate", "PasswordHash", "SecurityStamp" },
                values: new object[] { "cad42a06-6c82-4d9f-984e-50b9b334cffd", new DateTime(2025, 6, 25, 17, 2, 52, 859, DateTimeKind.Local).AddTicks(2668), "AQAAAAIAAYagAAAAEL/EWIbA/91db1M9p48HNWb+0bQK7+J59YxB+veP9xOyyFiFgp0MoBKGGCQrzkrqhw==", "627f4292-51d7-4a73-aad8-66af10cc476a" });
        }
    }
}
