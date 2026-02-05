using System;
using Microsoft.EntityFrameworkCore.Migrations;

#nullable disable

namespace PYS.Infrastructure.Persistence.Migrations
{
    /// <inheritdoc />
    public partial class AddPeriodsAndLocation : Migration
    {
        /// <inheritdoc />
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.CreateTable(
                name: "Locations",
                columns: table => new
                {
                    Id = table.Column<Guid>(type: "uniqueidentifier", nullable: false),
                    Name = table.Column<string>(type: "nvarchar(max)", nullable: false),
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
                    table.PrimaryKey("PK_Locations", x => x.Id);
                    table.ForeignKey(
                        name: "FK_Locations_AspNetUsers_AppUserCreatedId",
                        column: x => x.AppUserCreatedId,
                        principalTable: "AspNetUsers",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                    table.ForeignKey(
                        name: "FK_Locations_AspNetUsers_AppUserModifiedId",
                        column: x => x.AppUserModifiedId,
                        principalTable: "AspNetUsers",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                });

            migrationBuilder.CreateTable(
                name: "Periods",
                columns: table => new
                {
                    Id = table.Column<Guid>(type: "uniqueidentifier", nullable: false),
                    Year = table.Column<int>(type: "int", nullable: false),
                    Term = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    StartDate = table.Column<DateTime>(type: "datetime2", nullable: false),
                    EndDate = table.Column<DateTime>(type: "datetime2", nullable: false),
                    HasStaging = table.Column<bool>(type: "bit", nullable: false),
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
                    table.PrimaryKey("PK_Periods", x => x.Id);
                    table.ForeignKey(
                        name: "FK_Periods_AspNetUsers_AppUserCreatedId",
                        column: x => x.AppUserCreatedId,
                        principalTable: "AspNetUsers",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                    table.ForeignKey(
                        name: "FK_Periods_AspNetUsers_AppUserModifiedId",
                        column: x => x.AppUserModifiedId,
                        principalTable: "AspNetUsers",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                });

            migrationBuilder.CreateTable(
                name: "FormTypesPeriods",
                columns: table => new
                {
                    FormTypesId = table.Column<Guid>(type: "uniqueidentifier", nullable: false),
                    PeriodsId = table.Column<Guid>(type: "uniqueidentifier", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_FormTypesPeriods", x => new { x.FormTypesId, x.PeriodsId });
                    table.ForeignKey(
                        name: "FK_FormTypesPeriods_FormTypes_FormTypesId",
                        column: x => x.FormTypesId,
                        principalTable: "FormTypes",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                    table.ForeignKey(
                        name: "FK_FormTypesPeriods_Periods_PeriodsId",
                        column: x => x.PeriodsId,
                        principalTable: "Periods",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                });

            migrationBuilder.CreateTable(
                name: "LocationPeriods",
                columns: table => new
                {
                    LocationsId = table.Column<Guid>(type: "uniqueidentifier", nullable: false),
                    PeriodsId = table.Column<Guid>(type: "uniqueidentifier", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_LocationPeriods", x => new { x.LocationsId, x.PeriodsId });
                    table.ForeignKey(
                        name: "FK_LocationPeriods_Locations_LocationsId",
                        column: x => x.LocationsId,
                        principalTable: "Locations",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                    table.ForeignKey(
                        name: "FK_LocationPeriods_Periods_PeriodsId",
                        column: x => x.PeriodsId,
                        principalTable: "Periods",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                });

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

            migrationBuilder.CreateIndex(
                name: "IX_FormTypesPeriods_PeriodsId",
                table: "FormTypesPeriods",
                column: "PeriodsId");

            migrationBuilder.CreateIndex(
                name: "IX_LocationPeriods_PeriodsId",
                table: "LocationPeriods",
                column: "PeriodsId");

            migrationBuilder.CreateIndex(
                name: "IX_Locations_AppUserCreatedId",
                table: "Locations",
                column: "AppUserCreatedId");

            migrationBuilder.CreateIndex(
                name: "IX_Locations_AppUserModifiedId",
                table: "Locations",
                column: "AppUserModifiedId");

            migrationBuilder.CreateIndex(
                name: "IX_Periods_AppUserCreatedId",
                table: "Periods",
                column: "AppUserCreatedId");

            migrationBuilder.CreateIndex(
                name: "IX_Periods_AppUserModifiedId",
                table: "Periods",
                column: "AppUserModifiedId");
        }

        /// <inheritdoc />
        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DropTable(
                name: "FormTypesPeriods");

            migrationBuilder.DropTable(
                name: "LocationPeriods");

            migrationBuilder.DropTable(
                name: "Locations");

            migrationBuilder.DropTable(
                name: "Periods");

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
        }
    }
}
