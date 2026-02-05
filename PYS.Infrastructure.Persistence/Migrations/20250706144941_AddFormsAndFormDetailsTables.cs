using System;
using Microsoft.EntityFrameworkCore.Migrations;

#nullable disable

namespace PYS.Infrastructure.Persistence.Migrations
{
    /// <inheritdoc />
    public partial class AddFormsAndFormDetailsTables : Migration
    {
        /// <inheritdoc />
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.UpdateData(
                table: "AspNetRoles",
                keyColumn: "Id",
                keyValue: new Guid("55929733-870b-4895-b62b-b9249fa86a06"),
                column: "ConcurrencyStamp",
                value: "9897f9e1-ef68-47da-92cf-efeffc4e581f");

            migrationBuilder.UpdateData(
                table: "AspNetUsers",
                keyColumn: "Id",
                keyValue: new Guid("037ffd4d-e033-4220-b044-c4c6eddad883"),
                columns: new[] { "ConcurrencyStamp", "CreatedDate", "PasswordHash", "SecurityStamp" },
                values: new object[] { "0c5c1b4c-1e2c-44f6-91a1-45e801e8f5e3", new DateTime(2025, 7, 6, 17, 49, 40, 647, DateTimeKind.Local).AddTicks(6785), "AQAAAAIAAYagAAAAEDi3brU9UmlPeFeO2AteZPQEsp4oz+nthmr0T7wq3NdX0emBnozRQHtaJlDi1kKg8Q==", "d0e66153-8fe6-4a03-9237-150b940b7476" });
        }

        /// <inheritdoc />
        protected override void Down(MigrationBuilder migrationBuilder)
        {
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
    }
}
