using System;
using Microsoft.EntityFrameworkCore.Migrations;

#nullable disable

namespace PYS.Infrastructure.Persistence.Migrations
{
    /// <inheritdoc />
    public partial class AddCascadeDeleteToJoinTables : Migration
    {
        /// <inheritdoc />
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.UpdateData(
                table: "AspNetRoles",
                keyColumn: "Id",
                keyValue: new Guid("55929733-870b-4895-b62b-b9249fa86a06"),
                column: "ConcurrencyStamp",
                value: "1bade523-4ccd-4f8a-a621-aa24948674b8");

            migrationBuilder.UpdateData(
                table: "AspNetUsers",
                keyColumn: "Id",
                keyValue: new Guid("037ffd4d-e033-4220-b044-c4c6eddad883"),
                columns: new[] { "ConcurrencyStamp", "CreatedDate", "PasswordHash", "SecurityStamp" },
                values: new object[] { "6cefa62c-eb75-409e-836e-c78297d87d4b", new DateTime(2025, 6, 17, 15, 7, 53, 29, DateTimeKind.Local).AddTicks(1279), "AQAAAAIAAYagAAAAEASnDWI1uhaLHvpFZ6sPrIHJR9R1gqmug3RWfEa0ciobDocONT1MimNolt16QxHA8A==", "7d91673c-6a38-4516-a06f-d77ec3bd76f5" });
        }

        /// <inheritdoc />
        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.UpdateData(
                table: "AspNetRoles",
                keyColumn: "Id",
                keyValue: new Guid("55929733-870b-4895-b62b-b9249fa86a06"),
                column: "ConcurrencyStamp",
                value: "fe1038f5-18b4-4293-a40b-8199143e1f2f");

            migrationBuilder.UpdateData(
                table: "AspNetUsers",
                keyColumn: "Id",
                keyValue: new Guid("037ffd4d-e033-4220-b044-c4c6eddad883"),
                columns: new[] { "ConcurrencyStamp", "CreatedDate", "PasswordHash", "SecurityStamp" },
                values: new object[] { "9a9fbd90-2c4b-48e4-a0f8-275d5fefc757", new DateTime(2025, 6, 3, 23, 35, 7, 490, DateTimeKind.Local).AddTicks(6211), "AQAAAAIAAYagAAAAEIRvYykm461v7dH3feP3wkvq7fhj3vXYhd2u6yQ6vK18Qxdq20u/sA1+o+Uz837ROQ==", "534a3595-516e-4039-bd74-3272e108f19d" });
        }
    }
}
