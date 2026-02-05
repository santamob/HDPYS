using System;
using Microsoft.EntityFrameworkCore.Migrations;

#nullable disable

namespace PYS.Infrastructure.Persistence.Migrations
{
    /// <inheritdoc />
    public partial class AddPeriodInUserTable : Migration
    {
        /// <inheritdoc />
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.CreateTable(
                name: "PeriodInUsers",
                columns: table => new
                {
                    Id = table.Column<Guid>(type: "uniqueidentifier", nullable: false),
                    PeriodId = table.Column<Guid>(type: "uniqueidentifier", nullable: false),
                    PeriodText = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    PerNr = table.Column<int>(type: "int", nullable: false),
                    Ename = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    Persg = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    Persk = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    OID = table.Column<int>(type: "int", nullable: false),
                    Orgtx = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    SID = table.Column<int>(type: "int", nullable: false),
                    PLSTX = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    Mail = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    MPernr = table.Column<int>(type: "int", nullable: true),
                    MMail = table.Column<string>(type: "nvarchar(max)", nullable: true),
                    AccAsgmnt = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    AccAsgmntT = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    Level = table.Column<int>(type: "int", nullable: true),
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
                    table.PrimaryKey("PK_PeriodInUsers", x => x.Id);
                    table.ForeignKey(
                        name: "FK_PeriodInUsers_AspNetUsers_AppUserCreatedId",
                        column: x => x.AppUserCreatedId,
                        principalTable: "AspNetUsers",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                    table.ForeignKey(
                        name: "FK_PeriodInUsers_AspNetUsers_AppUserModifiedId",
                        column: x => x.AppUserModifiedId,
                        principalTable: "AspNetUsers",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                    table.ForeignKey(
                        name: "FK_PeriodInUsers_Periods_PeriodId",
                        column: x => x.PeriodId,
                        principalTable: "Periods",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                });

            migrationBuilder.UpdateData(
                table: "AspNetRoles",
                keyColumn: "Id",
                keyValue: new Guid("55929733-870b-4895-b62b-b9249fa86a06"),
                column: "ConcurrencyStamp",
                value: "4c336b66-1829-4dab-8501-b7411467e22f");

            migrationBuilder.UpdateData(
                table: "AspNetUsers",
                keyColumn: "Id",
                keyValue: new Guid("037ffd4d-e033-4220-b044-c4c6eddad883"),
                columns: new[] { "ConcurrencyStamp", "CreatedDate", "PasswordHash", "SecurityStamp" },
                values: new object[] { "ef56bda1-7c80-4ea5-a165-b4e5b6608c48", new DateTime(2025, 7, 11, 21, 4, 54, 94, DateTimeKind.Local).AddTicks(2889), "AQAAAAIAAYagAAAAEF1FoKAaRd+4y3YiTc1VSaBAZgXoqbAyWPaadrPiy4D35CHilAOipTy9FWRB/+GAPw==", "482aa90e-f7ed-4e2a-90ae-36ccd2496747" });

            migrationBuilder.CreateIndex(
                name: "IX_PeriodInUsers_AppUserCreatedId",
                table: "PeriodInUsers",
                column: "AppUserCreatedId");

            migrationBuilder.CreateIndex(
                name: "IX_PeriodInUsers_AppUserModifiedId",
                table: "PeriodInUsers",
                column: "AppUserModifiedId");

            migrationBuilder.CreateIndex(
                name: "IX_PeriodInUsers_PeriodId",
                table: "PeriodInUsers",
                column: "PeriodId");
        }

        /// <inheritdoc />
        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DropTable(
                name: "PeriodInUsers");

            migrationBuilder.UpdateData(
                table: "AspNetRoles",
                keyColumn: "Id",
                keyValue: new Guid("55929733-870b-4895-b62b-b9249fa86a06"),
                column: "ConcurrencyStamp",
                value: "bf93faa4-da78-463b-b757-2be61e846cfe");

            migrationBuilder.UpdateData(
                table: "AspNetUsers",
                keyColumn: "Id",
                keyValue: new Guid("037ffd4d-e033-4220-b044-c4c6eddad883"),
                columns: new[] { "ConcurrencyStamp", "CreatedDate", "PasswordHash", "SecurityStamp" },
                values: new object[] { "c21a9a84-83c3-4283-89a9-1bcd7230d8b7", new DateTime(2025, 7, 7, 23, 18, 36, 544, DateTimeKind.Local).AddTicks(7310), "AQAAAAIAAYagAAAAEBvop/vApEKD36kz6abVqNxf6V0DMe1yQi+Ku88iQDG9jimavKNvx0scMy/6R5hHTQ==", "2c8bb5d8-0349-404d-a1f5-b4d670796cb2" });
        }
    }
}
