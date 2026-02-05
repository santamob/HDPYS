using System;
using Microsoft.EntityFrameworkCore.Migrations;

#nullable disable

namespace PYS.Infrastructure.Persistence.Migrations
{
    /// <inheritdoc />
    public partial class AddIndicatorsAndStages : Migration
    {
        /// <inheritdoc />
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.CreateTable(
                name: "Indicators",
                columns: table => new
                {
                    Id = table.Column<Guid>(type: "uniqueidentifier", nullable: false),
                    FormTypeId = table.Column<Guid>(type: "uniqueidentifier", nullable: false),
                    FormTypeText = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    IndicatorName = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    IndicatorPeriod = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    IndicatorDesc = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    IndicatorDetailDesc = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    IndicatorCategory = table.Column<string>(type: "nvarchar(max)", nullable: true),
                    IndicatorPlannedDesc = table.Column<string>(type: "nvarchar(max)", nullable: true),
                    IndicatorRealizedDesc = table.Column<string>(type: "nvarchar(max)", nullable: true),
                    IndicatorResultDesc = table.Column<string>(type: "nvarchar(max)", nullable: true),
                    DataSource = table.Column<int>(type: "int", nullable: false),
                    DataSourceText = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    DataCalculation = table.Column<int>(type: "int", nullable: false),
                    DataCalculationText = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    EvaluationType = table.Column<int>(type: "int", nullable: false),
                    EvaluationTypeText = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    IndicatorKminDesc = table.Column<double>(type: "float", nullable: false),
                    IndicatorKmaxDesc = table.Column<double>(type: "float", nullable: false),
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
                    table.PrimaryKey("PK_Indicators", x => x.Id);
                    table.ForeignKey(
                        name: "FK_Indicators_AspNetUsers_AppUserCreatedId",
                        column: x => x.AppUserCreatedId,
                        principalTable: "AspNetUsers",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                    table.ForeignKey(
                        name: "FK_Indicators_AspNetUsers_AppUserModifiedId",
                        column: x => x.AppUserModifiedId,
                        principalTable: "AspNetUsers",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                    table.ForeignKey(
                        name: "FK_Indicators_FormTypes_FormTypeId",
                        column: x => x.FormTypeId,
                        principalTable: "FormTypes",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                });

            migrationBuilder.CreateTable(
                name: "Stages",
                columns: table => new
                {
                    Id = table.Column<Guid>(type: "uniqueidentifier", nullable: false),
                    IndicatorId = table.Column<Guid>(type: "uniqueidentifier", nullable: false),
                    StageDesc = table.Column<string>(type: "nvarchar(max)", nullable: false),
                    StageLower = table.Column<decimal>(type: "decimal(18,2)", nullable: false),
                    StageTop = table.Column<decimal>(type: "decimal(18,2)", nullable: false),
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
                    table.PrimaryKey("PK_Stages", x => x.Id);
                    table.ForeignKey(
                        name: "FK_Stages_AspNetUsers_AppUserCreatedId",
                        column: x => x.AppUserCreatedId,
                        principalTable: "AspNetUsers",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                    table.ForeignKey(
                        name: "FK_Stages_AspNetUsers_AppUserModifiedId",
                        column: x => x.AppUserModifiedId,
                        principalTable: "AspNetUsers",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                    table.ForeignKey(
                        name: "FK_Stages_Indicators_IndicatorId",
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
                value: "841aa070-4d8f-493d-a9b3-039bf50e5055");

            migrationBuilder.UpdateData(
                table: "AspNetUsers",
                keyColumn: "Id",
                keyValue: new Guid("037ffd4d-e033-4220-b044-c4c6eddad883"),
                columns: new[] { "ConcurrencyStamp", "CreatedDate", "PasswordHash", "SecurityStamp" },
                values: new object[] { "cad42a06-6c82-4d9f-984e-50b9b334cffd", new DateTime(2025, 6, 25, 17, 2, 52, 859, DateTimeKind.Local).AddTicks(2668), "AQAAAAIAAYagAAAAEL/EWIbA/91db1M9p48HNWb+0bQK7+J59YxB+veP9xOyyFiFgp0MoBKGGCQrzkrqhw==", "627f4292-51d7-4a73-aad8-66af10cc476a" });

            migrationBuilder.CreateIndex(
                name: "IX_Indicators_AppUserCreatedId",
                table: "Indicators",
                column: "AppUserCreatedId");

            migrationBuilder.CreateIndex(
                name: "IX_Indicators_AppUserModifiedId",
                table: "Indicators",
                column: "AppUserModifiedId");

            migrationBuilder.CreateIndex(
                name: "IX_Indicators_FormTypeId",
                table: "Indicators",
                column: "FormTypeId");

            migrationBuilder.CreateIndex(
                name: "IX_Stages_AppUserCreatedId",
                table: "Stages",
                column: "AppUserCreatedId");

            migrationBuilder.CreateIndex(
                name: "IX_Stages_AppUserModifiedId",
                table: "Stages",
                column: "AppUserModifiedId");

            migrationBuilder.CreateIndex(
                name: "IX_Stages_IndicatorId",
                table: "Stages",
                column: "IndicatorId");
        }

        /// <inheritdoc />
        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DropTable(
                name: "Stages");

            migrationBuilder.DropTable(
                name: "Indicators");

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
    }
}
