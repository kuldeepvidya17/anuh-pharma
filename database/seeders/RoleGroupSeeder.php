<?php

namespace Database\Seeders;

use App\Models\RoleGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
// Initiator, HOD, QA, CFT/SME, Head QA/Designee, FP, View Only
// Extension, Root Cause Analysis, CAPA, Deviation, Change Control, Management Review, Effectiveness Check
$sites = [
    'Corporate/India',
    'Dewas'
];

$processes_roles = [
    'Extension' => ['Initiator', 'HOD/Designee', 'QA', 'Head QA/Designee', 'View Only', 'FP', 'Closed Record'],
    'Effectiveness Check' => ['Initiator', 'HOD/Designee', 'QA', 'Head QA/Designee', 'View Only', 'FP', 'Closed Record'],
    'Effectiveness Check' => ['Initiator', 'HOD/Designee', 'QA', 'Head QA/Designee', 'View Only', 'FP', 'Closed Record'],
    'OOS' => ['Initiator', 'HOD/Designee', 'QA', 'Head QA/Designee', 'View Only', 'FP', 'Closed Record'],
    'Change Control' => ['Initiator', 'HOD/Designee', 'QA', 'Head QA/Designee', 'View Only', 'FP', 'Closed Record'],
    'Lab Incident' => ['Initiator', 'HOD/Designee', 'QA', 'Head QA/Designee', 'View Only', 'FP', 'Closed Record'],
    'CAPA' => ['Initiator', 'HOD/Designee', 'QA', 'Head QA/Designee', 'View Only', 'FP', 'Closed Record'],
    'MarketComplaint' => ['Initiator', 'HOD/Designee', 'QA', 'Head QA/Designee', 'View Only', 'FP', 'Closed Record'],
];

foreach ($sites as $site) {
    foreach ($processes_roles as $process => $roles) {
        foreach ($roles as $role) {
            $group = new RoleGroup();
            $group->name = "$site-$process-$role";
            $group->description = "$site-$process-$role";
            $group->permission = json_encode(['read' => true, 'create' => true, 'edit' => true, 'delete' => true]);
            $group->save();
        }
    }
}
//CFT PRocess

$processes2 = [
    'Deviation','OOS',
    'MarketComplaint','CAPA','Lab Incident'];
$cft_roles = [
    "Production",
    "Warehouse",
    "Quality Control",
    "Quality Assurance",
    "Engineering",
    "Analytical Development Laboratory",
    "Process Development Laboratory / Kilo Lab",
    "Technology Transfer / Design",
    "Environment, Health & Safety",
    "Human Resource & Administration",
    "Information Technology",
    "Project Management",
    "Other1",
    "Other2",
    "Other3",
    "Other4",
    "Other5",
];

foreach ($processes2 as $process) 
        {
            foreach ($sites as $site) {
                foreach ($cft_roles as $role) {
                    $group = new RoleGroup();
                    $group->name = "$site-$process-$role";
                    $group->description = "$site-$process-$role";
                    $group->permission = json_encode(['read' => true, 'create' => true, 'edit' => true, 'delete' => true]);
                    $group->save();
                }
            }
        }
}
}
