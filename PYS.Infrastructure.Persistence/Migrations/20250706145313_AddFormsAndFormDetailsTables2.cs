using System;
using Microsoft.EntityFrameworkCore.Migrations;

#nullable disable

namespace PYS.Infrastructure.Persistence.Migrations
{
    /// <inheritdoc />
    public partial class AddFormsAndFormDetailsTables2 : Migration
    {
        /// <inheritdoc />
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.CreateTable(
                name: "Forms",
                columns: table => new
                {
                    Id = table.Column<Guid>(type: "uniqueidentifier", nullable: false),
                    FormTypeId = table.Column<Guid>(type: "uniqueidentifier", nullable: false),
                    FormTypeText = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    FormName = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    TotalWeight = table.Column<int>(type: "int", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_Forms", x => x.Id);
                });

            migrationBuilder.CreateTable(
                name: "FormDetails",
                columns: table => new
                {
                    Id = table.Column<Guid>(type: "uniqueidentifier", nullable: false),
                    FormId = table.Column<Guid>(type: "uniqueidentifier", nullable: false),
                    IndicatorId = table.Column<Guid>(type: "uniqueidentifier", nullable: false),
                    Weight = table.Column<int>(type: "int", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_FormDetails", x => x.Id);
                    table.ForeignKey(
                        name: "FK_FormDetails_Forms_FormId",
                        column: x => x.FormId,
                        principalTable: "Forms",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                    table.ForeignKey(
                        name: "FK_FormDetails_Indicators_IndicatorId",
                        column: x => x.IndicatorId,
                        principalTable: "Indicators",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                });

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

            migrationBuilder.CreateIndex(
                name: "IX_FormDetails_FormId",
                table: "FormDetails",
                column: "FormId");

            migrationBuilder.CreateIndex(
                name: "IX_FormDetails_IndicatorId",
                table: "FormDetails",
                column: "IndicatorId");
        }

        /// <inheritdoc />
        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DropTable(
                name: "FormDetails");

            migrationBuilder.DropTable(
                name: "Forms");

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
    }
}
