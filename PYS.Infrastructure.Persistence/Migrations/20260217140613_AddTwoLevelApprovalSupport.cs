using System;
using Microsoft.EntityFrameworkCore.Migrations;

#nullable disable

namespace PYS.Infrastructure.Persistence.Migrations
{
    /// <inheritdoc />
    public partial class AddTwoLevelApprovalSupport : Migration
    {
        /// <inheritdoc />
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.AddColumn<Guid>(
                name: "ApprovedBySecondManagerId",
                table: "EmployeeGoals",
                type: "uniqueidentifier",
                nullable: true);

            migrationBuilder.AddColumn<DateTime>(
                name: "FirstApprovalDate",
                table: "EmployeeGoals",
                type: "datetime2",
                nullable: true);

            migrationBuilder.AddColumn<DateTime>(
                name: "SecondApprovalDate",
                table: "EmployeeGoals",
                type: "datetime2",
                nullable: true);

            migrationBuilder.AddColumn<string>(
                name: "SecondManagerNote",
                table: "EmployeeGoals",
                type: "nvarchar(2000)",
                maxLength: 2000,
                nullable: true);

            migrationBuilder.UpdateData(
                table: "AspNetRoles",
                keyColumn: "Id",
                keyValue: new Guid("55929733-870b-4895-b62b-b9249fa86a04"),
                column: "ConcurrencyStamp",
                value: "059ffcd4-018a-4bb6-9ef9-bd0cbd99b30b");

            migrationBuilder.UpdateData(
                table: "AspNetRoles",
                keyColumn: "Id",
                keyValue: new Guid("55929733-870b-4895-b62b-b9249fa86a06"),
                column: "ConcurrencyStamp",
                value: "553ed259-7132-4a97-9d77-b8156f9bcd2b");

            migrationBuilder.UpdateData(
                table: "AspNetRoles",
                keyColumn: "Id",
                keyValue: new Guid("a5d3b6f8-1c2e-4d3f-9a8b-7c6e5d4f3a2b"),
                column: "ConcurrencyStamp",
                value: "5f20281e-96c8-4f6a-92b8-99d97880fe2d");

            migrationBuilder.UpdateData(
                table: "AspNetRoles",
                keyColumn: "Id",
                keyValue: new Guid("c8f5d3a1-4b6c-5d7e-9f8a-3b4c5d6e7f8a"),
                column: "ConcurrencyStamp",
                value: "095a9c47-c9d2-4817-a85b-ba728493db94");

            migrationBuilder.UpdateData(
                table: "AspNetRoles",
                keyColumn: "Id",
                keyValue: new Guid("d9a6e4b2-5c7d-6e8f-1a9b-4c5d6e7f8a9b"),
                column: "ConcurrencyStamp",
                value: "48b1a164-b5aa-49dd-bc73-369c7d16723f");

            migrationBuilder.UpdateData(
                table: "AspNetRoles",
                keyColumn: "Id",
                keyValue: new Guid("e1b7f5c3-6d8e-7f9a-2b1c-5d6e7f8a9b1c"),
                column: "ConcurrencyStamp",
                value: "09363536-9c05-400c-8607-127c3b829f01");

            migrationBuilder.UpdateData(
                table: "AspNetRoles",
                keyColumn: "Id",
                keyValue: new Guid("f2c8a6d4-7e9f-8a1b-3c2d-6e7f8a9b1c2d"),
                column: "ConcurrencyStamp",
                value: "b18b4e8d-c248-4ba1-bf14-ef66ca130ee4");

            migrationBuilder.UpdateData(
                table: "AspNetUsers",
                keyColumn: "Id",
                keyValue: new Guid("037ffd4d-e033-4220-b044-c4c6eddad883"),
                columns: new[] { "ConcurrencyStamp", "CreatedDate", "PasswordHash", "SecurityStamp" },
                values: new object[] { "2efdf3d3-1e68-48b3-9d5f-f9fabc10fe47", new DateTime(2026, 2, 17, 17, 6, 11, 829, DateTimeKind.Local).AddTicks(4189), "AQAAAAIAAYagAAAAEI7A7A+eJb82jHwJdpsXWxMyEy2zZZhm+zcSuCA29tsQMqNXOp9VX5HjR33CvGIXbA==", "ac8df481-87da-4936-b416-9ad5cfb1684d" });
        }

        /// <inheritdoc />
        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DropColumn(
                name: "ApprovedBySecondManagerId",
                table: "EmployeeGoals");

            migrationBuilder.DropColumn(
                name: "FirstApprovalDate",
                table: "EmployeeGoals");

            migrationBuilder.DropColumn(
                name: "SecondApprovalDate",
                table: "EmployeeGoals");

            migrationBuilder.DropColumn(
                name: "SecondManagerNote",
                table: "EmployeeGoals");

            migrationBuilder.UpdateData(
                table: "AspNetRoles",
                keyColumn: "Id",
                keyValue: new Guid("55929733-870b-4895-b62b-b9249fa86a04"),
                column: "ConcurrencyStamp",
                value: "d89e87ae-1e2c-42fc-b131-eabd2c1c12fb");

            migrationBuilder.UpdateData(
                table: "AspNetRoles",
                keyColumn: "Id",
                keyValue: new Guid("55929733-870b-4895-b62b-b9249fa86a06"),
                column: "ConcurrencyStamp",
                value: "cab25ed1-5669-4b3a-9053-5546d142d004");

            migrationBuilder.UpdateData(
                table: "AspNetRoles",
                keyColumn: "Id",
                keyValue: new Guid("a5d3b6f8-1c2e-4d3f-9a8b-7c6e5d4f3a2b"),
                column: "ConcurrencyStamp",
                value: "2b7c7201-e0e9-4454-9754-f5a982f8970a");

            migrationBuilder.UpdateData(
                table: "AspNetRoles",
                keyColumn: "Id",
                keyValue: new Guid("c8f5d3a1-4b6c-5d7e-9f8a-3b4c5d6e7f8a"),
                column: "ConcurrencyStamp",
                value: "3d21ec94-82a3-4d5d-9dc1-927378757592");

            migrationBuilder.UpdateData(
                table: "AspNetRoles",
                keyColumn: "Id",
                keyValue: new Guid("d9a6e4b2-5c7d-6e8f-1a9b-4c5d6e7f8a9b"),
                column: "ConcurrencyStamp",
                value: "5480f546-4cf7-4b6f-95f4-14e0c43341a4");

            migrationBuilder.UpdateData(
                table: "AspNetRoles",
                keyColumn: "Id",
                keyValue: new Guid("e1b7f5c3-6d8e-7f9a-2b1c-5d6e7f8a9b1c"),
                column: "ConcurrencyStamp",
                value: "6d765bea-5995-41a2-9143-0aca2fa3ca58");

            migrationBuilder.UpdateData(
                table: "AspNetRoles",
                keyColumn: "Id",
                keyValue: new Guid("f2c8a6d4-7e9f-8a1b-3c2d-6e7f8a9b1c2d"),
                column: "ConcurrencyStamp",
                value: "feb59e38-fa65-443d-bcce-f87689a35545");

            migrationBuilder.UpdateData(
                table: "AspNetUsers",
                keyColumn: "Id",
                keyValue: new Guid("037ffd4d-e033-4220-b044-c4c6eddad883"),
                columns: new[] { "ConcurrencyStamp", "CreatedDate", "PasswordHash", "SecurityStamp" },
                values: new object[] { "22ff5c38-208e-419c-8049-942fc3b45d41", new DateTime(2026, 2, 12, 14, 35, 38, 800, DateTimeKind.Local).AddTicks(9681), "AQAAAAIAAYagAAAAEBBkmrzotkSolJZU9lUOmLxgBCDT4U+J1y7uIEKQRO3qZ2XAoRN+8qfz2TQY1hE4Vg==", "9c0d7e4e-ae3c-4abe-88d6-1722bfb80a81" });
        }
    }
}
