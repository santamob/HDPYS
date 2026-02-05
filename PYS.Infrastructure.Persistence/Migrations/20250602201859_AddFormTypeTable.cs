using System;
using Microsoft.EntityFrameworkCore.Migrations;

#nullable disable

namespace PYS.Infrastructure.Persistence.Migrations
{
    /// <inheritdoc />
    public partial class AddFormTypeTable : Migration
    {
        /// <inheritdoc />
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.CreateTable(
                name: "FormTypes",
                columns: table => new
                {
                    Id = table.Column<Guid>(type: "uniqueidentifier", nullable: false),
                    FormTypeName = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    AppUserCreatedId = table.Column<Guid>(type: "uniqueidentifier", nullable: false),
                    CreatedDate = table.Column<DateTime>(type: "datetime2", nullable: false),
                    CreatedIp = table.Column<string>(type: "nvarchar(max)", nullable: true),
                    AppUserModifiedId = table.Column<Guid>(type: "uniqueidentifier", nullable: true),
                    ModifiedDate = table.Column<DateTime>(type: "datetime2", nullable: true),
                    ModifiedIp = table.Column<string>(type: "nvarchar(max)", nullable: true),
                    IsActive = table.Column<bool>(type: "bit", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_FormTypes", x => x.Id);
                    table.ForeignKey(
                        name: "FK_FormTypes_AspNetUsers_AppUserCreatedId",
                        column: x => x.AppUserCreatedId,
                        principalTable: "AspNetUsers",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                    table.ForeignKey(
                        name: "FK_FormTypes_AspNetUsers_AppUserModifiedId",
                        column: x => x.AppUserModifiedId,
                        principalTable: "AspNetUsers",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                });

            migrationBuilder.UpdateData(
                table: "AspNetRoles",
                keyColumn: "Id",
                keyValue: new Guid("55929733-870b-4895-b62b-b9249fa86a06"),
                column: "ConcurrencyStamp",
                value: "cbd6fad4-50c5-44a0-bd10-dbcb45f77492");

            migrationBuilder.UpdateData(
                table: "AspNetUsers",
                keyColumn: "Id",
                keyValue: new Guid("037ffd4d-e033-4220-b044-c4c6eddad883"),
                columns: new[] { "ConcurrencyStamp", "CreatedDate", "PasswordHash", "SecurityStamp" },
                values: new object[] { "d6f081b7-cc54-4dcb-8fca-190caa06b92f", new DateTime(2025, 6, 2, 23, 18, 58, 679, DateTimeKind.Local).AddTicks(9242), "AQAAAAIAAYagAAAAEEIooE86iOwO/L6HfOA1j6Dl+Hcv/imRl0/ZHfdvrQuQLzGTAHqF7nRpjJpoputYog==", "9794fa55-725d-4573-a06e-aa60d3bef078" });

            migrationBuilder.CreateIndex(
                name: "IX_FormTypes_AppUserCreatedId",
                table: "FormTypes",
                column: "AppUserCreatedId");

            migrationBuilder.CreateIndex(
                name: "IX_FormTypes_AppUserModifiedId",
                table: "FormTypes",
                column: "AppUserModifiedId");
        }

        /// <inheritdoc />
        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DropTable(
                name: "FormTypes");

            migrationBuilder.UpdateData(
                table: "AspNetRoles",
                keyColumn: "Id",
                keyValue: new Guid("55929733-870b-4895-b62b-b9249fa86a06"),
                column: "ConcurrencyStamp",
                value: "6f660bb2-1a96-47a3-a0b7-81398feb467b");

            migrationBuilder.UpdateData(
                table: "AspNetUsers",
                keyColumn: "Id",
                keyValue: new Guid("037ffd4d-e033-4220-b044-c4c6eddad883"),
                columns: new[] { "ConcurrencyStamp", "CreatedDate", "PasswordHash", "SecurityStamp" },
                values: new object[] { "3bde6817-beba-4100-b5fd-4bd541ea9baa", new DateTime(2025, 5, 21, 10, 45, 30, 969, DateTimeKind.Local).AddTicks(6466), "AQAAAAIAAYagAAAAEJcgxbkbTal8AiURaEbnLv4YbTPx3G0S7ZhJPnLuEJ18c1Z0gbdd/e00VPYwOqYCOg==", "69c48223-b1fa-4720-ac0e-5334c5f3a64a" });
        }
    }
}
