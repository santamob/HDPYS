using System;
using Microsoft.EntityFrameworkCore.Migrations;

#nullable disable

namespace PYS.Infrastructure.Persistence.Migrations
{
    /// <inheritdoc />
    public partial class AddEmployeeGoalsTable : Migration
    {
        /// <inheritdoc />
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.CreateTable(
                name: "EmployeeGoals",
                columns: table => new
                {
                    Id = table.Column<Guid>(type: "uniqueidentifier", nullable: false),
                    PeriodId = table.Column<Guid>(type: "uniqueidentifier", nullable: false),
                    PeriodInUserId = table.Column<Guid>(type: "uniqueidentifier", nullable: false),
                    IndicatorId = table.Column<Guid>(type: "uniqueidentifier", nullable: true),
                    ApprovedByManagerId = table.Column<Guid>(type: "uniqueidentifier", nullable: true),
                    GoalTitle = table.Column<string>(type: "nvarchar(500)", maxLength: 500, nullable: false),
                    GoalDescription = table.Column<string>(type: "nvarchar(4000)", maxLength: 4000, nullable: true),
                    TargetValue = table.Column<decimal>(type: "decimal(18,2)", precision: 18, scale: 2, nullable: false),
                    TargetUnit = table.Column<string>(type: "nvarchar(50)", maxLength: 50, nullable: true),
                    Weight = table.Column<decimal>(type: "decimal(5,2)", precision: 5, scale: 2, nullable: false),
                    StartDate = table.Column<DateTime>(type: "datetime2", nullable: true),
                    EndDate = table.Column<DateTime>(type: "datetime2", nullable: true),
                    ActualValue = table.Column<decimal>(type: "decimal(18,2)", precision: 18, scale: 2, nullable: true),
                    AchievementRate = table.Column<decimal>(type: "decimal(18,2)", precision: 18, scale: 2, nullable: true),
                    CalculatedScore = table.Column<decimal>(type: "decimal(18,2)", precision: 18, scale: 2, nullable: true),
                    WeightedScore = table.Column<decimal>(type: "decimal(18,2)", precision: 18, scale: 2, nullable: true),
                    Status = table.Column<int>(type: "int", nullable: false),
                    ApprovalDate = table.Column<DateTime>(type: "datetime2", nullable: true),
                    ManagerNote = table.Column<string>(type: "nvarchar(2000)", maxLength: 2000, nullable: true),
                    EmployeeNote = table.Column<string>(type: "nvarchar(2000)", maxLength: 2000, nullable: true),
                    RejectionCount = table.Column<int>(type: "int", nullable: false),
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
                    table.PrimaryKey("PK_EmployeeGoals", x => x.Id);
                    table.ForeignKey(
                        name: "FK_EmployeeGoals_AspNetUsers_AppUserCreatedId",
                        column: x => x.AppUserCreatedId,
                        principalTable: "AspNetUsers",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                    table.ForeignKey(
                        name: "FK_EmployeeGoals_AspNetUsers_AppUserModifiedId",
                        column: x => x.AppUserModifiedId,
                        principalTable: "AspNetUsers",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                    table.ForeignKey(
                        name: "FK_EmployeeGoals_Indicators_IndicatorId",
                        column: x => x.IndicatorId,
                        principalTable: "Indicators",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                    table.ForeignKey(
                        name: "FK_EmployeeGoals_PeriodInUsers_PeriodInUserId",
                        column: x => x.PeriodInUserId,
                        principalTable: "PeriodInUsers",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                    table.ForeignKey(
                        name: "FK_EmployeeGoals_Periods_PeriodId",
                        column: x => x.PeriodId,
                        principalTable: "Periods",
                        principalColumn: "Id",
                        onDelete: ReferentialAction.Restrict);
                });

            migrationBuilder.CreateIndex(
                name: "IX_EmployeeGoals_AppUserCreatedId",
                table: "EmployeeGoals",
                column: "AppUserCreatedId");

            migrationBuilder.CreateIndex(
                name: "IX_EmployeeGoals_AppUserModifiedId",
                table: "EmployeeGoals",
                column: "AppUserModifiedId");

            migrationBuilder.CreateIndex(
                name: "IX_EmployeeGoals_IndicatorId",
                table: "EmployeeGoals",
                column: "IndicatorId");

            migrationBuilder.CreateIndex(
                name: "IX_EmployeeGoals_PeriodId_PeriodInUserId",
                table: "EmployeeGoals",
                columns: new[] { "PeriodId", "PeriodInUserId" });

            migrationBuilder.CreateIndex(
                name: "IX_EmployeeGoals_PeriodInUserId_IndicatorId",
                table: "EmployeeGoals",
                columns: new[] { "PeriodInUserId", "IndicatorId" },
                unique: true,
                filter: "[IndicatorId] IS NOT NULL AND [IsActive] = 1");

            migrationBuilder.CreateIndex(
                name: "IX_EmployeeGoals_Status",
                table: "EmployeeGoals",
                column: "Status");
        }

        /// <inheritdoc />
        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DropTable(
                name: "EmployeeGoals");
        }
    }
}
