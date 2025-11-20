<?php

namespace App\Http\Controllers;

use App\Models\AboutJtik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InformasiController extends Controller
{
    /**
     * Display information for public (non-admin)
     */
    public function index()
    {
        $about = AboutJtik::first();
        
        if (!$about) {
            $about = $this->createDefaultData();
        }
        
        return view('informasi', compact('about'));
    }

    /**
     * Display information page for admin
     */
    public function adminIndex()
    {
        $about = AboutJtik::first();
        
        if (!$about) {
            $about = $this->createDefaultData();
        }
        
        return view('admin.information.index', compact('about'));
    }

    /**
     * Show edit form
     */
    public function edit()
    {
        $about = AboutJtik::first();
        
        if (!$about) {
            $about = $this->createDefaultData();
        }
        
        return view('admin.information.edit', compact('about'));
    }

    /**
     * Update information - FIXED FOR FORM DATA
     */
    public function update(Request $request)
{
    try {
        DB::beginTransaction();

        $about = AboutJtik::first();
        
        if (!$about) {
            $about = new AboutJtik();
        }

        // Process hero stats data
        $heroStatsData = [
            'title' => $request->input('hero_stats.title'),
            'subtitle' => $request->input('hero_stats.subtitle'),
            'students' => $request->input('hero_stats.students'),
            'lecturers' => $request->input('hero_stats.lecturers'),
            'accreditation_badge' => $request->input('hero_stats.accreditation_badge')
        ];

        // Process info data (existing code)
        $infoData = [
            'address' => $request->input('info.address'),
            'phone' => $request->input('info.phone'),
            'email' => $request->input('info.email'),
            'maps_url' => $request->input('info.maps_url'),
            'accreditation' => $request->input('info.accreditation'),
            'operational_hours' => $this->processOperationalHours($request),
            'study_programs' => $this->processStudyPrograms($request)
        ];

        // Process detail data (existing code)
        $detailData = [
            'history' => $request->input('detail.history'),
            'vision' => $request->input('detail.vision'),
            'missions' => $this->processMissions($request),
            'achievements' => $this->processAchievements($request),
            'lecturers' => $this->processLecturers($request),
            'staff' => $this->processStaff($request)
        ];

            // Remove null values from arrays
            $infoData['operational_hours'] = array_filter($infoData['operational_hours'], function($item) {
                return !empty($item['day']) && !empty($item['hours']);
            });
            
            $infoData['study_programs'] = array_filter($infoData['study_programs']);
            $detailData['missions'] = array_filter($detailData['missions']);
            $detailData['lecturers'] = array_filter($detailData['lecturers']);
            $detailData['staff'] = array_filter($detailData['staff']);
            $detailData['achievements'] = array_filter($detailData['achievements'], function($item) {
                return !empty($item['year']) && !empty($item['title']);
            });

            // Reset array keys
            $infoData['operational_hours'] = array_values($infoData['operational_hours']);
            $infoData['study_programs'] = array_values($infoData['study_programs']);
            $detailData['missions'] = array_values($detailData['missions']);
            $detailData['achievements'] = array_values($detailData['achievements']);
            $detailData['lecturers'] = array_values($detailData['lecturers']);
            $detailData['staff'] = array_values($detailData['staff']);

             $about->hero_stats = $heroStatsData;
        $about->info = $infoData;
        $about->detail = $detailData;
        $about->updated_at = now();
        $about->save();

        DB::commit();

        return redirect()->route('admin.information.index')
            ->with('success', 'Informasi JTIK berhasil diperbarui!');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()
            ->with('error', 'Gagal memperbarui informasi: ' . $e->getMessage())
            ->withInput();
    }
}

    /**
     * Process operational hours from form
     */
    private function processOperationalHours(Request $request)
    {
        $hours = [];
        $operationalHours = $request->input('info.operational_hours', []);
        
        foreach ($operationalHours as $hour) {
            if (!empty($hour['day']) && !empty($hour['hours'])) {
                $hours[] = [
                    'day' => $hour['day'],
                    'hours' => $hour['hours']
                ];
            }
        }
        
        return $hours;
    }

    /**
     * Process study programs from form
     */
    private function processStudyPrograms(Request $request)
    {
        $programs = $request->input('info.study_programs', []);
        return array_filter($programs);
    }

    /**
     * Process missions from form
     */
    private function processMissions(Request $request)
    {
        $missions = $request->input('detail.missions', []);
        return array_filter($missions);
    }

    /**
     * Process achievements from form
     */
    private function processAchievements(Request $request)
    {
        $achievements = [];
        $achievementData = $request->input('detail.achievements', []);
        
        foreach ($achievementData as $achievement) {
            if (!empty($achievement['year']) && !empty($achievement['title'])) {
                $achievements[] = [
                    'year' => $achievement['year'],
                    'title' => $achievement['title']
                ];
            }
        }
        
        return $achievements;
    }

    /**
     * Process lecturers from form
     */
    private function processLecturers(Request $request)
    {
        $lecturers = $request->input('detail.lecturers', []);
        return array_filter($lecturers);
    }

    /**
     * Process staff from form
     */
    private function processStaff(Request $request)
    {
        $staff = $request->input('detail.staff', []);
        return array_filter($staff);
    }

    /**
     * Create default data
     */
    private function createDefaultData()
    {
        $defaultInfo = [
            "address" => "Jl. A.H. Nasution No.105, Cibiru, Bandung",
            "phone" => "+62 22 1234 5678",
            "email" => "jtik@universitas.ac.id",
            "maps_url" => "https://maps.app.goo.gl/AK2r4G17Py3crq4k7",
            "operational_hours" => [
                ["day" => "Senin - Kamis", "hours" => "07:00 - 16:00"],
                ["day" => "Jumat", "hours" => "07:00 - 16:30"],
                ["day" => "Sabtu", "hours" => "08:00 - 14:00"],
                ["day" => "Minggu", "hours" => "Libur"]
            ],
            "study_programs" => [
                "Teknik Informatika (S1)",
                "Sistem Informasi (S1)",
                "Teknik Komputer (S1)"
            ],
            "accreditation" => "A (Unggul)"
        ];

        $defaultDetail = [
            "history" => "Jurusan Teknik Informatika dan Komputer (JTIK) didirikan pada tahun 1998 dengan visi menjadi pusat unggulan dalam pendidikan dan penelitian di bidang teknologi informasi dan komputer. Sejak berdiri, JTIK telah menghasilkan lulusan yang kompeten dan berdaya saing tinggi di industri teknologi.",
            "vision" => "Menjadi jurusan unggulan yang menghasilkan lulusan berkompeten di bidang teknologi informasi dan komputer yang mampu bersaing di tingkat nasional dan internasional.",
            "missions" => [
                "Menyelenggarakan pendidikan berkualitas di bidang TI dan Komputer",
                "Melakukan penelitian inovatif yang bermanfaat bagi masyarakat",
                "Mengembangkan kerjasama dengan industri dan institusi lain",
                "Mendorong pengabdian masyarakat berbasis teknologi"
            ],
            "achievements" => [
                ["year" => "2023", "title" => "Juara 1 National Programming Contest"],
                ["year" => "2022", "title" => "Best Paper Award di International Conference"],
                ["year" => "2021", "title" => "Akreditasi A untuk semua program studi"],
                ["year" => "2020", "title" => "Inovasi Teknologi Terbaik Tingkat Nasional"]
            ],
            "lecturers" => [
                "Prof. Dr. Ahmad Santoso, M.Kom.",
                "Dr. Siti Aminah, M.T.",
                "Dr. Budi Raharjo, M.Sc.",
                "Dian Pratiwi, M.Kom.",
                "Rizki Pratama, M.T.I."
            ],
            "staff" => [
                "Maya Sari, S.Adm. - Kepala Tata Usaha",
                "Rudi Hermawan - Staf Administrasi",
                "Sari Indah, A.Md. - Staf Akademik"
            ]
        ];

        $about = AboutJtik::create([
            'info' => $defaultInfo,
            'detail' => $defaultDetail,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return $about;
    }
}