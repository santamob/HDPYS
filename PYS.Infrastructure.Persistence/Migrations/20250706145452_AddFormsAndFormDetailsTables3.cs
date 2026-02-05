using System;
using Microsoft.EntityFrameworkCore.Migrations;

#nullable disable

namespace PYS.Infrastructure.Persistence.Migrations
{
    /// <inheritdoc />
    public partial class AddFormsAndFormDetailsTables3 : Migration
    {
        /// <inheritdoc />
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.AddColumn<Guid>(
                name: "AppUserCreatedId",
                table: "Forms",
                type: "uniqueidentifier",
                nullable: false,
                defaultValue: new Guid("00000000-0000-0000-0000-000000000000"));

            migrationBuilder.AddColumn<Guid>(
                name: "AppUserModifiedId",
                table: "Forms",
                type: "uniqueidentifier",
                nullable: true);

            migrationBuilder.AddColumn<DateTime>(
                name: "CreatedDate",
                table: "Forms",
                type: "datetime2",
                nullable: false,
                defaultValue: new DateTime(1, 1, 1, 0, 0, 0, 0, DateTimeKind.Unspecified));

            migrationBuilder.AddColumn<string>(
                name: "CreatedIp",
                table: "Forms",
                type: "nvarchar(max)",
                nullable: true);

            migrationBuilder.AddColumn<bool>(
                name: "IsActive",
                table: "Forms",
                type: "bit",
                nullable: false,
                defaultValue: false);

            migrationBuilder.AddColumn<DateTime>(
                name: "ModifiedDate",
                table: "Forms",
                type: "datetime2",
                nullable: true);

            migrationBuilder.AddColumn<string>(
                name: "ModifiedIp",
                table: "Forms",
                type: "nvarchar(max)",
                nullable: true);

            migrationBuilder.AddColumn<Guid>(
                name: "AppUserCreatedId",
                table: "FormDetails",
                type: "uniqueidentifier",
                nullable: false,
                defaultValue: new Guid("00000000-0000-0000-0000-000000000000"));

            migrationBuilder.AddColumn<Guid>(
                name: "AppUserModifiedId",
                table: "FormDetails",
                type: "uniqueidentifier",
                nullable: true);

            migrationBuilder.AddColumn<DateTime>(
                name: "CreatedDate",
                table: "FormDetails",
                type: "datetime2",
                nullable: false,
                defaultValue: new DateTime(1, 1, 1, 0, 0, 0, 0, DateTimeKind.Unspecified));

            migrationBuilder.AddColumn<string>(
                name: "CreatedIp",
                table: "FormDetails",
                type: "nvarchar(max)",
                nullable: true);

            migrationBuilder.AddColumn<bool>(
                name: "IsActive",
                table: "FormDetails",
                type: "bit",
                nullable: false,
                defaultValue: false);

            migrationBuilder.AddColumn<DateTime>(
                name: "ModifiedDate",
                table: "FormDetails",
                type: "datetime2",
                nullable: true);

            migrationBuilder.AddColumn<string>(
                name: "ModifiedIp",
                table: "FormDetails",
                type: "nvarchar(max)",
                nullable: true);

            migrationBuilder.UpdateData(
                table: "AspNetRoles",
                keyColumn: "Id",
                keyValue: new Guid("55929733-870b-4895-b62b-b9249fa86a06"),
                column: "ConcurrencyStamp",
                value: "f695f426-eb84-437f-89fd-a55b50c9f813");

            migrationBuilder.UpdateData(
                table: "AspNetUsers",
                keyColumn: "Id",
                keyValue: new Guid("037ffd4d-e033-4220-b044-c4c6eddad883"),
                columns: new[] { "ConcurrencyStamp", "CreatedDate", "PasswordHash", "SecurityStamp" },
                values: new object[] { "4dd8df53-bcb9-4dcb-8290-0cc2964efc11", new DateTime(2025, 7, 6, 17, 54, 52, 64, DateTimeKind.Local).AddTicks(9338), "AQAAAAIAAYagAAAAEN3TkHjTOkxlxwXGalRs5k6bwZ3onpUYGYDzCVa4c4wgSOAzk/rzPbBqJJpsNNxFdA==", "3b564a88-8f8c-4811-9227-510552859078" });

            migrationBuilder.CreateIndex(
                name: "IX_Forms_AppUserCreatedId",
                table: "Forms",
                column: "AppUserCreatedId");

            migrationBuilder.CreateIndex(
                name: "IX_Forms_AppUserModifiedId",
                table: "Forms",
                column: "AppUserModifiedId");

            migrationBuilder.CreateIndex(
                name: "IX_FormDetails_AppUserCreatedId",
                table: "FormDetails",
                column: "AppUserCreatedId");

            migrationBuilder.CreateIndex(
                name: "IX_FormDetails_AppUserModifiedId",
                table: "FormDetails",
                column: "AppUserModifiedId");

            migrationBuilder.AddForeignKey(
                name: "FK_FormDetails_AspNetUsers_AppUserCreatedId",
                table: "FormDetails",
                column: "AppUserCreatedId",
                principalTable: "AspNetUsers",
                principalColumn: "Id",
                onDelete: ReferentialAction.Restrict);

            migrationBuilder.AddForeignKey(
                name: "FK_FormDetails_AspNetUsers_AppUserModifiedId",
                table: "FormDetails",
                column: "AppUserModifiedId",
                principalTable: "AspNetUsers",
                principalColumn: "Id",
                onDelete: ReferentialAction.Restrict);

            migrationBuilder.AddForeignKey(
                name: "FK_Forms_AspNetUsers_AppUserCreatedId",
                table: "Forms",
                column: "AppUserCreatedId",
                principalTable: "AspNetUsers",
                principalColumn: "Id",
                onDelete: ReferentialAction.Restrict);

            migrationBuilder.AddForeignKey(
                name: "FK_Forms_AspNetUsers_AppUserModifiedId",
                table: "Forms",
                column: "AppUserModifiedId",
                principalTable: "AspNetUsers",
                principalColumn: "Id",
                onDelete: ReferentialAction.Restrict);
        }

        /// <inheritdoc />
        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DropForeignKey(
                name: "FK_FormDetails_AspNetUsers_AppUserCreatedId",
                table: "FormDetails");

            migrationBuilder.DropForeignKey(
                name: "FK_FormDetails_AspNetUsers_AppUserModifiedId",
                table: "FormDetails");

            migrationBuilder.DropForeignKey(
                name: "FK_Forms_AspNetUsers_AppUserCreatedId",
                table: "Forms");

            migrationBuilder.DropForeignKey(
                name: "FK_Forms_AspNetUsers_AppUserModifiedId",
                table: "Forms");

            migrationBuilder.DropIndex(
                name: "IX_Forms_AppUserCreatedId",
                table: "Forms");

            migrationBuilder.DropIndex(
                name: "IX_Forms_AppUserModifiedId",
                table: "Forms");

            migrationBuilder.DropIndex(
                name: "IX_FormDetails_AppUserCreatedId",
                table: "FormDetails");

            migrationBuilder.DropIndex(
                name: "IX_FormDetails_AppUserModifiedId",
                table: "FormDetails");

            migrationBuilder.DropColumn(
                name: "AppUserCreatedId",
                table: "Forms");

            migrationBuilder.DropColumn(
                name: "AppUserModifiedId",
                table: "Forms");

            migrationBuilder.DropColumn(
                name: "CreatedDate",
                table: "Forms");

            migrationBuilder.DropColumn(
                name: "CreatedIp",
                table: "Forms");

            migrationBuilder.DropColumn(
                name: "IsActive",
                table: "Forms");

            migrationBuilder.DropColumn(
                name: "ModifiedDate",
                table: "Forms");

            migrationBuilder.DropColumn(
                name: "ModifiedIp",
                table: "Forms");

            migrationBuilder.DropColumn(
                name: "AppUserCreatedId",
                table: "FormDetails");

            migrationBuilder.DropColumn(
                name: "AppUserModifiedId",
                table: "FormDetails");

            migrationBuilder.DropColumn(
                name: "CreatedDate",
                table: "FormDetails");

            migrationBuilder.DropColumn(
                name: "CreatedIp",
                table: "FormDetails");

            migrationBuilder.DropColumn(
                name: "IsActive",
                table: "FormDetails");

            migrationBuilder.DropColumn(
                name: "ModifiedDate",
                table: "FormDetails");

            migrationBuilder.DropColumn(
                name: "ModifiedIp",
                table: "FormDetails");

            migrationBuilder.UpdateData(
                table: "AspNetRoles",
                keyColumn: "Id",
                keyValue: new Guid("55929733-870b-4895-b62b-b9249fa86a06"),
                column: "ConcurrencyStamp",
                value: "3a758410-39ff-4982-a53c-09f0ad3b301c");

            migrationBuilder.UpdateData(
                table: "AspNetUsers",
                keyColumn: "Id",
                keyValue: new Guid("037ffd4d-e033-4220-b044-c4c6eddad883"),
                columns: new[] { "ConcurrencyStamp", "CreatedDate", "PasswordHash", "SecurityStamp" },
                values: new object[] { "7b0d43e0-eecc-4b0b-8cf3-4d017d7f83d3", new DateTime(2025, 7, 6, 17, 53, 12, 831, DateTimeKind.Local).AddTicks(9207), "AQAAAAIAAYagAAAAENml+mIZjTYxcBCuTJKRulfduvmpuBhA2kyYaMmggU2qc8afBJ3if+QFKvXDnAMH5g==", "424571c3-81fe-463b-8bcf-ae8aec6024fa" });
        }
    }
}
