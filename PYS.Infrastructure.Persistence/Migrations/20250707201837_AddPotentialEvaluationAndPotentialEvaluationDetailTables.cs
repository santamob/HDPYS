using System;
using Microsoft.EntityFrameworkCore.Migrations;

#nullable disable

namespace PYS.Infrastructure.Persistence.Migrations
{
    /// <inheritdoc />
    public partial class AddPotentialEvaluationAndPotentialEvaluationDetailTables : Migration
    {
        /// <inheritdoc />
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.CreateTable(
                name: "PotentialEvaluations",
                columns: table => new
                {
                    Id = table.Column<Guid>(type: "uniqueidentifier", nullable: false),
                    CriteriaName = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    MinValue = table.Column<decimal>(type: "decimal(18,2)", nullable: false),
                    MaxValue = table.Column<decimal>(type: "decimal(18,2)", nullable: false),
                    PeriodId = table.Column<Guid>(type: "uniqueidentifier", nullable: false),
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
                    table.PrimaryKey("PK_PotentialEvaluations", x => x.Id);
                    table.ForeignKey(
                        name: "FK_PotentialEvaluations_AspNetUsers_AppUserCreatedId",
                        column: x => x.AppUserCreatedId,
                        principalTable: "AspNetUsers",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                    table.ForeignKey(
                        name: "FK_PotentialEvaluations_AspNetUsers_AppUserModifiedId",
                        column: x => x.AppUserModifiedId,
                        principalTable: "AspNetUsers",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                    table.ForeignKey(
                        name: "FK_PotentialEvaluations_Periods_PeriodId",
                        column: x => x.PeriodId,
                        principalTable: "Periods",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                });

            migrationBuilder.CreateTable(
                name: "PotentialEvaluationDetails",
                columns: table => new
                {
                    Id = table.Column<Guid>(type: "uniqueidentifier", nullable: false),
                    PotentialEvaluationId = table.Column<Guid>(type: "uniqueidentifier", nullable: false),
                    CriteriaDetailName = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    CriteriaDetailStatus = table.Column<bool>(type: "bit", nullable: false),
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
                    table.PrimaryKey("PK_PotentialEvaluationDetails", x => x.Id);
                    table.ForeignKey(
                        name: "FK_PotentialEvaluationDetails_AspNetUsers_AppUserCreatedId",
                        column: x => x.AppUserCreatedId,
                        principalTable: "AspNetUsers",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                    table.ForeignKey(
                        name: "FK_PotentialEvaluationDetails_AspNetUsers_AppUserModifiedId",
                        column: x => x.AppUserModifiedId,
                        principalTable: "AspNetUsers",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                    table.ForeignKey(
                        name: "FK_PotentialEvaluationDetails_PotentialEvaluations_PotentialEvaluationId",
                        column: x => x.PotentialEvaluationId,
                        principalTable: "PotentialEvaluations",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                });

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

            migrationBuilder.CreateIndex(
                name: "IX_PotentialEvaluationDetails_AppUserCreatedId",
                table: "PotentialEvaluationDetails",
                column: "AppUserCreatedId");

            migrationBuilder.CreateIndex(
                name: "IX_PotentialEvaluationDetails_AppUserModifiedId",
                table: "PotentialEvaluationDetails",
                column: "AppUserModifiedId");

            migrationBuilder.CreateIndex(
                name: "IX_PotentialEvaluationDetails_PotentialEvaluationId",
                table: "PotentialEvaluationDetails",
                column: "PotentialEvaluationId");

            migrationBuilder.CreateIndex(
                name: "IX_PotentialEvaluations_AppUserCreatedId",
                table: "PotentialEvaluations",
                column: "AppUserCreatedId");

            migrationBuilder.CreateIndex(
                name: "IX_PotentialEvaluations_AppUserModifiedId",
                table: "PotentialEvaluations",
                column: "AppUserModifiedId");

            migrationBuilder.CreateIndex(
                name: "IX_PotentialEvaluations_PeriodId",
                table: "PotentialEvaluations",
                column: "PeriodId");
        }

        /// <inheritdoc />
        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DropTable(
                name: "PotentialEvaluationDetails");

            migrationBuilder.DropTable(
                name: "PotentialEvaluations");

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
        }
    }
}
