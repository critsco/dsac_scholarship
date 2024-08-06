<?php

namespace App\Imports;

use App\Events\NotificationPusherEvent;
use App\Models\EmailTemplate;
use App\Models\Notification;
use App\Models\NotificationUser;
use App\Models\Profile;
use App\Models\ProfileContactInformation;
use App\Models\RefExamSchedule;
use App\Models\StudentExam;
use App\Models\StudentExamResult;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;


class ExamResultImport implements ToCollection
{

    private $data;
    private $exam_category_id;

    public function __construct(array $data, int $exam_category_id)
    {
        $this->data = $data;
        $this->exam_category_id = $exam_category_id;
    }

    private $ret = [];
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $data = [];

        if ($this->exam_category_id === 1) {
            $data   = $this->college_exam_result($collection);
        } elseif ($this->exam_category_id === 2) {
            $data   = $this->graduate_studies_exam_result($collection);
        } elseif ($this->exam_category_id === 3) {
            $data   = $this->college_of_law_exam_result($collection);
        }

        $ret = [
            "success" => true,
            "data" => $data
        ];

        return response()->json($ret, 200);
    }

    public function college_exam_result(Collection $collection)
    {

        // College Exam Result
        $header = ["", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", ""]; // 21
        $data = [];

        foreach ($collection as $key => $value) {
            $col3 = isset($value[3]) ? $value[3] : null;
            $col4 = isset($value[4]) ? $value[4] : null;
            $col20 = isset($value[20]) ? $value[20] : null;
            $col21 = isset($value[21]) ? $value[21] : null;
            $col22 = isset($value[22]) ? $value[22] : null;
            $col23 = isset($value[23]) ? $value[23] : null;
            $col24 = isset($value[24]) ? $value[24] : null;
            $col25 = isset($value[25]) ? $value[25] : null;
            $col26 = isset($value[26]) ? $value[26] : null;
            $col27 = isset($value[27]) ? $value[27] : null;
            $col28 = isset($value[28]) ? $value[28] : null;
            $col29 = isset($value[29]) ? $value[29] : null;
            $col30 = isset($value[30]) ? $value[30] : null;
            $col31 = isset($value[31]) ? $value[31] : null;
            $col32 = isset($value[32]) ? $value[32] : null;
            $col33 = isset($value[33]) ? $value[33] : null;
            $col34 = isset($value[34]) ? $value[34] : null;
            $col35 = isset($value[35]) ? $value[35] : null;
            $col36 = isset($value[36]) ? $value[36] : null;
            $col37 = isset($value[37]) ? $value[37] : null;
            $col38 = isset($value[38]) ? $value[38] : null;

            if ($key == 0) {
                $col3  = strtoupper(str_replace(' ', '', $col3));
                $col4  = strtoupper(str_replace(' ', '', $col4));
                $col20 = strtoupper(str_replace(' ', '', $col20));
                $col21 = strtoupper(str_replace(' ', '', $col21));
                $col22 = strtoupper(str_replace(' ', '', $col22));
                $col23 = strtoupper(str_replace(' ', '', $col23));
                $col24 = strtoupper(str_replace(' ', '', $col24));
                $col25 = strtoupper(str_replace(' ', '', $col25));
                $col26 = strtoupper(str_replace(' ', '', $col26));
                $col27 = strtoupper(str_replace(' ', '', $col27));
                $col28 = strtoupper(str_replace(' ', '', $col28));
                $col29 = strtoupper(str_replace(' ', '', $col29));
                $col30 = strtoupper(str_replace(' ', '', $col30));
                $col31 = strtoupper(str_replace(' ', '', $col31));
                $col32 = strtoupper(str_replace(' ', '', $col32));
                $col33 = strtoupper(str_replace(' ', '', $col33));
                $col34 = strtoupper(str_replace(' ', '', $col34));
                $col35 = strtoupper(str_replace(' ', '', $col35));
                $col36 = strtoupper(str_replace(' ', '', $col36));
                $col37 = strtoupper(str_replace(' ', '', $col37));
                $col38 = strtoupper(str_replace(' ', '', $col38));

                $header = [
                    $col3, $col4, $col20, $col21, $col22, $col23, $col24, $col25, $col26, $col27, $col28, $col29, $col30, $col31, $col32, $col33, $col34, $col35, $col36, $col37, $col38,
                ];
            } else {
                $col3  = isset($value[3]) ? trim($value[3]) : null;
                $col3  = mb_convert_encoding($col3, 'UTF-8', 'auto');

                $col4  = isset($value[4]) ? trim($value[4]) : null;
                $col4  = mb_convert_encoding($col4, 'UTF-8', 'auto');

                $col20 = isset($value[20]) ? trim($value[20]) : null;
                $col20 = mb_convert_encoding($col20, 'UTF-8', 'auto');

                $col21 = isset($value[21]) ? trim($value[21]) : null;
                $col21 = mb_convert_encoding($col21, 'UTF-8', 'auto');

                $col22 = isset($value[22]) ? trim($value[22]) : null;
                $col22 = mb_convert_encoding($col22, 'UTF-8', 'auto');

                $col23 = isset($value[23]) ? trim($value[23]) : null;
                $col23 = mb_convert_encoding($col23, 'UTF-8', 'auto');

                $col24 = isset($value[24]) ? trim($value[24]) : null;
                $col24 = mb_convert_encoding($col24, 'UTF-8', 'auto');

                $col25 = isset($value[25]) ? trim($value[25]) : null;
                $col25 = mb_convert_encoding($col25, 'UTF-8', 'auto');

                $col26 = isset($value[26]) ? trim($value[26]) : null;
                $col26 = mb_convert_encoding($col26, 'UTF-8', 'auto');

                $col27 = isset($value[27]) ? trim($value[27]) : null;
                $col27 = mb_convert_encoding($col27, 'UTF-8', 'auto');

                $col28 = isset($value[28]) ? trim($value[28]) : null;
                $col28 = mb_convert_encoding($col28, 'UTF-8', 'auto');

                $col29 = isset($value[29]) ? trim($value[29]) : null;
                $col29 = mb_convert_encoding($col29, 'UTF-8', 'auto');

                $col30 = isset($value[30]) ? trim($value[30]) : null;
                $col30 = mb_convert_encoding($col30, 'UTF-8', 'auto');

                $col31 = isset($value[31]) ? trim($value[31]) : null;
                $col31 = mb_convert_encoding($col31, 'UTF-8', 'auto');

                $col32 = isset($value[32]) ? trim($value[32]) : null;
                $col32 = mb_convert_encoding($col32, 'UTF-8', 'auto');

                $col33 = isset($value[33]) ? trim($value[33]) : null;
                $col33 = mb_convert_encoding($col33, 'UTF-8', 'auto');

                $col34 = isset($value[34]) ? trim($value[34]) : null;
                $col34 = mb_convert_encoding($col34, 'UTF-8', 'auto');

                $col35 = isset($value[35]) ? trim($value[35]) : null;
                $col35 = mb_convert_encoding($col35, 'UTF-8', 'auto');

                $col36 = isset($value[36]) ? trim($value[36]) : null;
                $col36 = mb_convert_encoding($col36, 'UTF-8', 'auto');

                $col37 = isset($value[37]) ? trim($value[37]) : null;
                $col37 = mb_convert_encoding($col37, 'UTF-8', 'auto');

                $col38 = isset($value[38]) ? trim($value[38]) : null;
                $col38 = mb_convert_encoding($col38, 'UTF-8', 'auto');

                $data[] = [
                    $col3, $col4,  $col20, $col21, $col22, $col23, $col24, $col25, $col26, $col27, $col28, $col29, $col30, $col31, $col32, $col33, $col34, $col35, $col36, $col37, $col38,
                ];
            }
        }

        $ifheader = $header[0] == "STUDNO" && $header[1] == "NAME" && $header[2] == "ENSCALEDSCORE" && $header[3] == "ENPRECENTILERANK" && $header[4] == "ENSTANINE" && $header[5] == "ENQUALITYINDEX" && $header[6] == "MTSCALEDSCORE" && $header[7] == "MTPRECENTILERANK" && $header[8] == "MTSTANINE" && $header[9] == "MTQUALITYINDEX" && $header[10] == "SCSCALEDSCORE" && $header[11] == "SCPRECENTILERANK" && $header[12] == "SCSTANINE" && $header[13] == "SCQUALITYINDEX" && $header[14] == "CRSSCALEDSCORE" && $header[15] == "CRSPERCENTILERANK" && $header[16] == "CRSSTANINE" && $header[17] == "CRSQUALITYINDEX" && $header[18] == "RAW" && $header[19] == "SAI" && $header[20] == "VD";

        if ($ifheader && count($data) > 0) {
            $studentExamReultNotExist = [];

            foreach ($data as $key => $value) {
                $STUDNO = $value[0];
                $NAME = $value[1];
                $ENSCALEDSCORE = $value[2];
                $ENPRECENTILERANK = $value[3];
                $ENSTANINE = $value[4];
                $ENQUALITYINDEX = $value[5];
                $MTSCALEDSCORE = $value[6];
                $MTPRECENTILERANK = $value[7];
                $MTSTANINE = $value[8];
                $MTQUALITYINDEX = $value[9];
                $SCSCALEDSCORE = $value[10];
                $SCPRECENTILERANK = $value[11];
                $SCSTANINE = $value[12];
                $SCQUALITYINDEX = $value[13];
                $CRSSCALEDSCORE = $value[14];
                $CRSPERCENTILERANK = $value[15];
                $CRSSTANINE = $value[16];
                $CRSQUALITYINDEX = $value[17];
                $RAW = $value[18];
                $SAI = $value[19];
                $VD = $value[20];

                $findStudentExamResults = StudentExamResult::where('exam_sheet_number', $STUDNO)->first();

                if ($findStudentExamResults) {

                    $updateStudentExamResult = $findStudentExamResults->fill([
                        'exam_sheet_number' => $STUDNO,
                        'fullname' => $NAME,
                        'en_scaled_scrore' => $ENSCALEDSCORE,
                        'en_percentile_rank' => $ENPRECENTILERANK,
                        'en_stanine' => $ENSTANINE,
                        'en_quality_index' => $ENQUALITYINDEX,
                        'mt_scaled_scrore' => $MTSCALEDSCORE,
                        'mt_percentile_rank' => $MTPRECENTILERANK,
                        'mt_stanine' => $MTSTANINE,
                        'mt_quality_index' => $MTQUALITYINDEX,
                        'sc_scaled_scrore' => $SCSCALEDSCORE,
                        'sc_percentile_rank' => $SCPRECENTILERANK,
                        'sc_stanine' => $SCSTANINE,
                        'sc_quality_index' => $SCQUALITYINDEX,
                        'crs_scaled_score' => $CRSSCALEDSCORE,
                        'crs_percentile_rank' => $CRSPERCENTILERANK,
                        'crs_stanine' => $CRSSTANINE,
                        'crs_quality_index' => $CRSQUALITYINDEX,
                        'raw' => $RAW,
                        'sai' => $SAI,
                        'vd' => $VD,
                    ]);

                    if ($updateStudentExamResult->save()) {
                        $findStudentExamId = StudentExam::where('profile_id', $findStudentExamResults->profile_id)->first();

                        if ($findStudentExamId) {
                            $findStudentExamId->fill([
                                'exam_result' => "Available",
                            ])->save();
                        }

                        $findEmail = ProfileContactInformation::where('profile_id', $findStudentExamResults->profile_id)->where('status', 1)->latest()->first();
                        $findUserById = Profile::where('id', $findStudentExamResults->profile_id)->first();

                        if ($findEmail) {
                            $send_name = "";
                            $findUserProfile = Profile::where('id', $findStudentExamResults->profile_id)->first();
                            if ($findUserProfile) {
                                $send_name = $findUserProfile->first_name . " " . $findUserProfile->last_name;
                            }

                            // GENERATE PDF
                            $examDate = "";

                            $findExamDate = RefExamSchedule::where('id', $findStudentExamId->exam_schedule_id)->first();
                            if ($findExamDate) {
                                $examDate = $findExamDate->exam_date;
                            }

                            $fsuu_bg = base64_encode(file_get_contents(public_path('images/fsuu_logo_wobg.png')));
                            $fsuu_logo = base64_encode(file_get_contents(public_path('images/logo.png')));
                            $guidance_logo = base64_encode(file_get_contents(public_path('images/guidance_logo.png')));

                            // Decode the base64 string
                            $fsuu_bg_data = 'data:image/png;base64,' . $fsuu_bg;
                            $fsuu_logo_data = 'data:image/png;base64,' . $fsuu_logo;
                            $guidance_logo_data = 'data:image/png;base64,' . $guidance_logo;

                            $data = [
                                'fullname' => $findStudentExamResults->fullname,
                                'exam_date' => $examDate,
                                'exam_room' => 'Testing Room',
                                'fsuu_bg' => $fsuu_bg_data,
                                'fsuu_logo' => $fsuu_logo_data,
                                'guidance_logo' => $guidance_logo_data,

                                // Exam Result
                                'en_scaled_scrore' => $findStudentExamResults->en_scaled_scrore,
                                'en_percentile_rank' => $findStudentExamResults->en_percentile_rank,
                                'en_stanine' => $findStudentExamResults->en_stanine,
                                'en_quality_index' => $findStudentExamResults->en_quality_index,

                                'mt_scaled_scrore' => $findStudentExamResults->mt_scaled_scrore,
                                'mt_percentile_rank' => $findStudentExamResults->mt_percentile_rank,
                                'mt_stanine' => $findStudentExamResults->mt_stanine,
                                'mt_quality_index' => $findStudentExamResults->mt_quality_index,

                                'sc_scaled_scrore' => $findStudentExamResults->sc_scaled_scrore,
                                'sc_percentile_rank' => $findStudentExamResults->sc_percentile_rank,
                                'sc_stanine' => $findStudentExamResults->sc_stanine,
                                'sc_quality_index' => $findStudentExamResults->sc_quality_index,

                                'crs_scaled_score' => $findStudentExamResults->crs_scaled_score,
                                'crs_percentile_rank' => $findStudentExamResults->crs_percentile_rank,
                                'crs_stanine' => $findStudentExamResults->crs_stanine,
                                'crs_quality_index' => $findStudentExamResults->crs_quality_index,

                                'raw' => $findStudentExamResults->raw,
                                'sai' => $findStudentExamResults->sai,
                                'vd' => $findStudentExamResults->vd,
                            ];

                            $pdf = Pdf::loadView('pdf.college-exam-result-template', ["applicants" => collect($data)]);

                            $pdf->getDomPDF()->setHttpContext(
                                stream_context_create([
                                    'ssl' => [
                                        'allow_self_signed' => TRUE,
                                        'verify_peer' => FALSE,
                                        'verify_peer_name' => FALSE,
                                    ]
                                ])
                            );
                            $pdf->setPaper('A4', 'portrait');

                            // Save the PDF to the database
                            $newPDFPath = 'profiles/profile-' . $findUserById->id . '/pdfs/college_exam_result/' . Str::random(10) . '.pdf';

                            $pdfPath = Storage::disk('local')->put('public/' . $newPDFPath, $pdf->output());
                            $pdf->save($pdfPath);

                            $findUserProfile = Profile::where('id', $findStudentExamResults->profile_id)->first();

                            $attachment = [];

                            if ($findUserProfile) {
                                $attachmentupdateOrCreate = $findUserProfile->attachments()->updateOrCreate(
                                    [
                                        'attachmentable_id' => $findUserProfile->id,
                                        'file_description' => 'College Exam Result',
                                    ],
                                    [
                                        'file_name' => "College Exam Result",
                                        'file_path' => 'storage/' . $newPDFPath,
                                        'file_description' => "College Exam Result",
                                        'file_type' => "document",
                                    ]
                                );

                                if ($attachmentupdateOrCreate) {
                                    $attachment[] = [
                                        'url' => public_path('storage/' . $newPDFPath),
                                        'as' => 'College_Exam_Result.pdf',
                                    ];
                                }
                            }

                            // Send Email with PDF Attachment
                            $this->send_email([
                                'title' => "RESULTS",
                                'to_name' => $findStudentExamResults->fullname,
                                'position' => "FSUU GUIDANCE",
                                'to_email' => $findEmail->email,
                                'sender_name' => $send_name,
                                "system_id" => 3,

                                // Send the email with the PDF attachment
                                'attachment' => $attachment,
                            ]);
                        }

                        // Notification
                        if ($findUserById) {
                            $this->send_notification([
                                "title" => "Entrance Exam Result",
                                "description" => "Your exam result is now available. Please check your email for the result.",
                                "link" => "",
                                "link_origin" => $this->data['link_origin'],
                                "userIds" => [$findUserById->user_id],
                                "system_id" => 3,
                            ]);
                        }
                    }
                } else {
                    $studentExamReultNotExist[] = [
                        'exam_sheet_number' => $STUDNO,
                        'fullname' => $NAME,
                        'en_scaled_scrore' => $ENSCALEDSCORE,
                        'en_percentile_rank' => $ENPRECENTILERANK,
                        'en_stanine' => $ENSTANINE,
                        'en_quality_index' => $ENQUALITYINDEX,
                        'mt_scaled_scrore' => $MTSCALEDSCORE,
                        'mt_percentile_rank' => $MTPRECENTILERANK,
                        'mt_stanine' => $MTSTANINE,
                        'mt_quality_index' => $MTQUALITYINDEX,
                        'sc_scaled_scrore' => $SCSCALEDSCORE,
                        'sc_percentile_rank' => $SCPRECENTILERANK,
                        'sc_stanine' => $SCSTANINE,
                        'sc_quality_index' => $SCQUALITYINDEX,
                        'crs_scaled_score' => $CRSSCALEDSCORE,
                        'crs_percentile_rank' => $CRSPERCENTILERANK,
                        'crs_stanine' => $CRSSTANINE,
                        'crs_quality_index' => $CRSQUALITYINDEX,
                        'raw' => $RAW,
                        'sai' => $SAI,
                        'vd' => $VD,
                    ];
                }
            }

            $ret = [
                "success" => true,
                "message" => "Excel Data Uploaded",
                "studentExamReultNotExist" => $studentExamReultNotExist,

            ];
        } else {
            $ret = [
                "success" => false,
                "message" => "File format is not correct. Please download the correct format and try again.",
            ];
        }
    }

    public function graduate_studies_exam_result(Collection $collection)
    {
        // Graduate Studies Exam Result
        $header = ["", "", "", "", "", "", ""];
        $data = [];

        foreach ($collection as $key => $value) {
            $col2 = isset($value[2]) ? $value[2] : null;
            $col3 = isset($value[3]) ? $value[3] : null;
            $col6 = isset($value[6]) ? $value[6] : null;
            $col7 = isset($value[7]) ? $value[7] : null;
            $col8 = isset($value[8]) ? $value[8] : null;
            $col9 = isset($value[9]) ? $value[9] : null;
            $col10 = isset($value[10]) ? $value[10] : null;

            if ($key == 0) {
                $col2 = strtoupper(str_replace(' ', '', $col2));
                $col3 = strtoupper(str_replace(' ', '', $col3));
                $col6 = strtoupper(str_replace(' ', '', $col6));
                $col7 = strtoupper(str_replace(' ', '', $col7));
                $col8 = strtoupper(str_replace(' ', '', $col8));
                $col9 = strtoupper(str_replace(' ', '', $col9));
                $col10 = strtoupper(str_replace(' ', '', $col10));

                $header = [
                    $col2, $col3,  $col6, $col7, $col8, $col9, $col10,
                ];
            } else {
                $col2  = isset($value[2]) ? trim($value[2]) : null;
                $col2  = mb_convert_encoding($col2, 'UTF-8', 'auto');

                $col3  = isset($value[3]) ? trim($value[3]) : null;
                $col3  = mb_convert_encoding($col3, 'UTF-8', 'auto');

                $col6  = isset($value[6]) ? trim($value[6]) : null;
                $col6  = mb_convert_encoding($col6, 'UTF-8', 'auto');

                $col7  = isset($value[7]) ? trim($value[7]) : null;
                $col7  = mb_convert_encoding($col7, 'UTF-8', 'auto');

                $col8  = isset($value[8]) ? trim($value[8]) : null;
                $col8  = mb_convert_encoding($col8, 'UTF-8', 'auto');

                $col9  = isset($value[9]) ? trim($value[9]) : null;
                $col9  = mb_convert_encoding($col9, 'UTF-8', 'auto');

                $col10  = isset($value[10]) ? trim($value[10]) : null;
                $col10  = mb_convert_encoding($col10, 'UTF-8', 'auto');

                if (!empty($col2) && !empty($col6)  && !empty($col7) && !empty($col8) && !empty($col9) && !empty($col10)) {
                    $data[] = [
                        $col2, $col3,  $col6, $col7, $col8, $col9, $col10,
                    ];
                }
            }
        }

        $ifheader = $header[0] == "STUDNO" && $header[1] == "NAME" && $header[2] == "V" && $header[3] == "Q" && $header[4] == "IR" && $header[5] == "SS" && $header[6] == "PR";

        if ($ifheader && count($data) > 0) {
            $studentExamReultNotExist = [];

            foreach ($data as $key => $value) {
                $STUDNO = $value[0];
                $NAME = $value[1];
                $V = $value[2];
                $Q = $value[3];
                $IR = $value[4];
                $SS = $value[5];
                $PR = $value[6];

                $findStudentExamResults = StudentExamResult::where('exam_sheet_number', $STUDNO)->first();

                if ($findStudentExamResults) {

                    $updateStudentExamResult = $findStudentExamResults->fill([
                        'exam_sheet_number' => $STUDNO,
                        'fullname' => $NAME,
                        'v' => $V,
                        'q' => $Q,
                        'ir' => $IR,
                        'ss' => $SS,
                        'pr' => $PR,

                    ]);

                    if ($updateStudentExamResult->save()) {
                        $findStudentExamId = StudentExam::where('profile_id', $findStudentExamResults->profile_id)->first();

                        if ($findStudentExamId) {
                            $findStudentExamId->fill([
                                'exam_result' => "Available",
                            ])->save();
                        }

                        $findEmail = ProfileContactInformation::where('profile_id', $findStudentExamResults->profile_id)->where('status', 1)->latest()->first();
                        $findUserById = Profile::where('id', $findStudentExamResults->profile_id)->first();

                        if ($findEmail) {
                            $send_name = "";
                            $findUserProfile = Profile::where('id', $findStudentExamResults->profile_id)->first();
                            if ($findUserProfile) {
                                $send_name = $findUserProfile->first_name . " " . $findUserProfile->last_name;
                            }

                            // GENERATE PDF
                            $examDate = "";

                            $findExamDate = RefExamSchedule::where('id', $findStudentExamId->exam_schedule_id)->first();
                            if ($findExamDate) {
                                $examDate = $findExamDate->exam_date;
                            }

                            $fsuu_bg = base64_encode(file_get_contents(public_path('images/fsuu_logo_wobg.png')));
                            $fsuu_logo = base64_encode(file_get_contents(public_path('images/logo.png')));
                            $guidance_logo = base64_encode(file_get_contents(public_path('images/guidance_logo.png')));

                            // Decode the base64 string
                            $fsuu_bg_data = 'data:image/png;base64,' . $fsuu_bg;
                            $fsuu_logo_data = 'data:image/png;base64,' . $fsuu_logo;
                            $guidance_logo_data = 'data:image/png;base64,' . $guidance_logo;

                            $data = [
                                'fullname' => $findStudentExamResults->fullname,
                                'exam_date' => $examDate,
                                'exam_room' => 'Testing Room',
                                'fsuu_bg' => $fsuu_bg_data,
                                'fsuu_logo' => $fsuu_logo_data,
                                'guidance_logo' => $guidance_logo_data,

                                // Exam Result
                                'v' => $findStudentExamResults->v,
                                'q' => $findStudentExamResults->q,
                                'ir' => $findStudentExamResults->ir,
                                'ss' => $findStudentExamResults->ss,
                                'pr' => $findStudentExamResults->pr,
                            ];

                            // Graduate Studies
                            $pdf = Pdf::loadView('pdf.graduate-exam-result-template', ["applicants" => collect($data)]);

                            $pdf->getDomPDF()->setHttpContext(
                                stream_context_create([
                                    'ssl' => [
                                        'allow_self_signed' => TRUE,
                                        'verify_peer' => FALSE,
                                        'verify_peer_name' => FALSE,
                                    ]
                                ])
                            );
                            $pdf->setPaper('A4', 'portrait');

                            // Save the PDF to the database
                            $newPDFPath = 'profiles/profile-' . $findUserById->id . '/pdfs/graduate_studies_exam_result/' . Str::random(10) . '.pdf';

                            $pdfPath = Storage::disk('local')->put('public/' . $newPDFPath, $pdf->output());
                            $pdf->save($pdfPath);

                            $findUserProfile = Profile::where('id', $findStudentExamResults->profile_id)->first();

                            $attachment = [];

                            if ($findUserProfile) {
                                $attachmentupdateOrCreate =  $findUserProfile->attachments()->updateOrCreate(
                                    [
                                        'attachmentable_id' => $findUserProfile->id,
                                        'file_description' => 'Graduate Studies Exam Result',
                                    ],
                                    [
                                        'file_name' => "Graduate Studies Exam Result",
                                        'file_path' => 'storage/' . $newPDFPath,
                                        'file_description' => "Graduate Studies Exam Result",
                                        'file_type' => "document",
                                    ]
                                );

                                if ($attachmentupdateOrCreate) {
                                    $attachment[] = [
                                        'url' => public_path('storage/' . $newPDFPath),
                                        'as' => 'College_Exam_Result.pdf',
                                    ];
                                }

                                // Send Email with PDF Attachment
                                $this->send_email([
                                    'title' => "RESULTS",
                                    'to_name' => $findStudentExamResults->fullname,
                                    'position' => "FSUU GUIDANCE",
                                    'to_email' => $findEmail->email,
                                    'sender_name' => $send_name,
                                    "system_id" => 3,

                                    // Send the email with the PDF attachment
                                    'attachment' => $attachment,
                                ]);
                            }
                        }

                        // Notification 
                        if ($findUserById) {
                            $this->send_notification([
                                "title" => "Entrance Exam Result",
                                "description" => "Your exam result is now available. Please check your email for the result.",
                                "link" => "",
                                "link_origin" => $this->data['link_origin'],
                                "userIds" => [$findUserById->user_id],
                                "system_id" => 3,
                            ]);
                        }
                    }
                } else {
                    $studentExamReultNotExist[] = [
                        'exam_sheet_number' => $STUDNO,
                        'fullname' => $NAME,
                        'v' => $V,
                        'q' => $Q,
                        'ir' => $IR,
                        'ss' => $SS,
                        'pr' => $PR,

                    ];
                }
            }
            $ret = [
                "success" => true,
                "message" => "Excel Data Uploaded",
                "studentExamReultNotExist" => $studentExamReultNotExist,

            ];
        } else {
            $ret = [
                "success" => false,
                "message" => "File format is not correct. Please download the correct format and try again.",
            ];
        }
    }

    public function college_of_law_exam_result(Collection $collection)
    {
        // College of Law Exam Result
        $header = ["", "", "", "", "", "", ""];
        $data = [];

        foreach ($collection as $key => $value) {
            $col2 = isset($value[2]) ? $value[2] : null;
            $col3 = isset($value[3]) ? $value[3] : null;
            $col6 = isset($value[6]) ? $value[6] : null;
            $col7 = isset($value[7]) ? $value[7] : null;
            $col8 = isset($value[8]) ? $value[8] : null;
            $col9 = isset($value[9]) ? $value[9] : null;
            $col10 = isset($value[10]) ? $value[10] : null;

            if ($key == 0) {
                $col2 = strtoupper(str_replace(' ', '', $col2));
                $col3 = strtoupper(str_replace(' ', '', $col3));
                $col6 = strtoupper(str_replace(' ', '', $col6));
                $col7 = strtoupper(str_replace(' ', '', $col7));
                $col8 = strtoupper(str_replace(' ', '', $col8));
                $col9 = strtoupper(str_replace(' ', '', $col9));
                $col10 = strtoupper(str_replace(' ', '', $col10));

                $header = [
                    $col2, $col3,  $col6, $col7, $col8, $col9, $col10,
                ];
            } else {
                $col2  = isset($value[2]) ? trim($value[2]) : null;
                $col2  = mb_convert_encoding($col2, 'UTF-8', 'auto');

                $col3  = isset($value[3]) ? trim($value[3]) : null;
                $col3  = mb_convert_encoding($col3, 'UTF-8', 'auto');

                $col6  = isset($value[6]) ? trim($value[6]) : null;
                $col6  = mb_convert_encoding($col6, 'UTF-8', 'auto');

                $col7  = isset($value[7]) ? trim($value[7]) : null;
                $col7  = mb_convert_encoding($col7, 'UTF-8', 'auto');

                $col8  = isset($value[8]) ? trim($value[8]) : null;
                $col8  = mb_convert_encoding($col8, 'UTF-8', 'auto');

                $col9  = isset($value[9]) ? trim($value[9]) : null;
                $col9  = mb_convert_encoding($col9, 'UTF-8', 'auto');

                $col10  = isset($value[10]) ? trim($value[10]) : null;
                $col10  = mb_convert_encoding($col10, 'UTF-8', 'auto');

                if (!empty($col2) && !empty($col6)  && !empty($col7) && !empty($col8) && !empty($col9) && !empty($col10)) {
                    $data[] = [
                        $col2, $col3,  $col6, $col7, $col8, $col9, $col10,
                    ];
                }
            }
        }

        $ifheader = $header[0] == "STUDNO" && $header[1] == "NAME" && $header[2] == "CT" && $header[3] == "VA" && $header[4] == "QA" && $header[5] == "SS" && $header[6] == "PR";

        if ($ifheader && count($data) > 0) {
            $studentExamReultNotExist = [];

            foreach ($data as $key => $value) {
                $STUDNO = $value[0];
                $NAME = $value[1];
                $CT = $value[2];
                $VA = $value[3];
                $QA = $value[4];
                $SS = $value[5];
                $PR = $value[6];

                $findStudentExamResults = StudentExamResult::where('exam_sheet_number', $STUDNO)->first();

                if ($findStudentExamResults) {

                    $updateStudentExamResult = $findStudentExamResults->fill([
                        'exam_sheet_number' => $STUDNO,
                        'fullname' => $NAME,
                        'ct' => $CT,
                        'va' => $VA,
                        'qa' => $QA,
                        'ss' => $SS,
                        'pr' => $PR,

                    ]);

                    if ($updateStudentExamResult->save()) {
                        $findStudentExamId = StudentExam::where('profile_id', $findStudentExamResults->profile_id)->first();

                        if ($findStudentExamId) {
                            $findStudentExamId->fill([
                                'exam_result' => "Available",
                            ])->save();
                        }

                        $findEmail = ProfileContactInformation::where('profile_id', $findStudentExamResults->profile_id)->where('status', 1)->latest()->first();
                        $findUserById = Profile::where('id', $findStudentExamResults->profile_id)->first();

                        if ($findEmail) {
                            $send_name = "";
                            $findUserProfile = Profile::where('id', $findStudentExamResults->profile_id)->first();
                            if ($findUserProfile) {
                                $send_name = $findUserProfile->first_name . " " . $findUserProfile->last_name;
                            }

                            // GENERATE PDF
                            $examDate = "";

                            $findExamDate = RefExamSchedule::where('id', $findStudentExamId->exam_schedule_id)->first();
                            if ($findExamDate) {
                                $examDate = $findExamDate->exam_date;
                            }

                            $fsuu_bg = base64_encode(file_get_contents(public_path('images/fsuu_logo_wobg.png')));
                            $fsuu_logo = base64_encode(file_get_contents(public_path('images/logo.png')));
                            $guidance_logo = base64_encode(file_get_contents(public_path('images/guidance_logo.png')));

                            // Decode the base64 string
                            $fsuu_bg_data = 'data:image/png;base64,' . $fsuu_bg;
                            $fsuu_logo_data = 'data:image/png;base64,' . $fsuu_logo;
                            $guidance_logo_data = 'data:image/png;base64,' . $guidance_logo;

                            $data = [
                                'fullname' => $findStudentExamResults->fullname,
                                'exam_date' => $examDate,
                                'exam_room' => 'Testing Room',
                                'fsuu_bg' => $fsuu_bg_data,
                                'fsuu_logo' => $fsuu_logo_data,
                                'guidance_logo' => $guidance_logo_data,

                                // Exam Result
                                'ct' => $findStudentExamResults->ct,
                                'va' => $findStudentExamResults->va,
                                'qa' => $findStudentExamResults->qa,
                                'ss' => $findStudentExamResults->ss,
                                'pr' => $findStudentExamResults->pr,
                            ];

                            $pdf = Pdf::loadView('pdf.law-exam-result-template', ["applicants" => collect($data)]);

                            $pdf->getDomPDF()->setHttpContext(
                                stream_context_create([
                                    'ssl' => [
                                        'allow_self_signed' => TRUE,
                                        'verify_peer' => FALSE,
                                        'verify_peer_name' => FALSE,
                                    ]
                                ])
                            );
                            $pdf->setPaper('A4', 'portrait');

                            // Save the PDF to the database
                            $newPDFPath = 'profiles/profile-' . $findUserById->id . '/pdfs/college_of_studies_exam_result/' . Str::random(10) . '.pdf';

                            $pdfPath = Storage::disk('local')->put('public/' . $newPDFPath, $pdf->output());
                            $pdf->save($pdfPath);

                            // Save the PDF to the database
                            $findUserProfile = Profile::where('id', $findStudentExamResults->profile_id)->first();

                            $attachment = [];

                            if ($findUserProfile) {
                                $attachmentupdateOrCreate = $findUserProfile->attachments()->updateOrCreate(
                                    [
                                        'attachmentable_id' => $findUserProfile->id,
                                        'file_description' => "College of Law Exam Result",
                                    ],
                                    [
                                        'file_name' => "College of Law Exam Result",
                                        'file_path' => 'storage/' . $newPDFPath,
                                        'file_description' => "College of Law Exam Result",
                                        'file_type' => "document",
                                    ]
                                );

                                if ($attachmentupdateOrCreate) {
                                    $attachment[] = [
                                        'url' => public_path('storage/' . $newPDFPath),
                                        'as' => 'College_Exam_Result.pdf',
                                    ];
                                }
                            }

                            // Send Email with PDF Attachment
                            $this->send_email([
                                'title' => "RESULTS",
                                'to_name' => $findStudentExamResults->fullname,
                                'position' => "FSUU GUIDANCE",
                                'to_email' => $findEmail->email,
                                'sender_name' => $send_name,
                                "system_id" => 3,

                                // Send the email with the PDF attachment
                                'attachment' => $attachment,
                            ]);
                        }

                        // Notification 
                        if ($findUserById) {
                            $this->send_notification([
                                "title" => "Entrance Exam Result",
                                "description" => "Your exam result is now available. Please check your email for the result.",
                                "link" => "",
                                "link_origin" => $this->data['link_origin'],
                                "userIds" => [$findUserById->user_id],
                                "system_id" => 3,
                            ]);
                        }
                    }
                } else {
                    $studentExamReultNotExist[] = [
                        'exam_sheet_number' => $STUDNO,
                        'fullname' => $NAME,
                        'ct' => $CT,
                        'va' => $VA,
                        'qa' => $QA,
                        'ss' => $SS,
                        'pr' => $PR,

                    ];
                }
            }
            $ret = [
                "success" => true,
                "message" => "Excel Data Uploaded",
                "studentExamReultNotExist" => $studentExamReultNotExist,

            ];
        } else {
            $ret = [
                "success" => false,
                "message" => "File format is not correct. Please download the correct format and try again.",
            ];
        }
    }

    public function getMessage()
    {
        return $this->ret;
    }

    public function pusher_notification($message)
    {
        event(new NotificationPusherEvent($message));
    }

    function send_notification($options)
    {
        $title = array_key_exists("title", $options) ? $options["title"] : "";

        if (array_key_exists("title", $options) && array_key_exists("system_id", $options)) {
            $title = $options["title"];
            $description = array_key_exists("description", $options) ? $options["description"] : "";
            $user_role_id = array_key_exists("user_role_id", $options) ? $options["user_role_id"] : "";
            $link = array_key_exists("link", $options) ? $options["link"] : "";
            $link_origin = array_key_exists("link_origin", $options) ? $options["link_origin"] : "";
            $link_id = array_key_exists("link_id", $options) ? $options["link_id"] : "";
            $system_id = array_key_exists("system_id", $options) ? $options["system_id"] : "";
            $userIds = array_key_exists("userIds", $options) ? $options["userIds"] : [];

            $dataNotifications = [
                "title" => $title,
                "description" => $description,
                "user_role_id" => $user_role_id,
                "link" => $link,
                "link_id" => $link_id,
                "system_id" => $system_id,
                "created_by" => auth()->user()->id,
            ];

            $queryNotification = Notification::create($dataNotifications);

            if ($queryNotification) {
                foreach ($userIds as $key => $value) {
                    NotificationUser::create([
                        "notification_id" => $queryNotification->id,
                        "user_id" => $value,
                    ]);

                    $this->pusher_notification([
                        "type" => "notification",
                        "link_origin" => $link_origin,
                        "user_id" => $value,
                    ]);
                }

                return [
                    "success" => true,
                    "message" => "Notification sent successfully."
                ];
            }
        } else {
            return [
                "success" => false,
                "message" => "title and system_id are required."
            ];
        }
    }

    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    function create_attachment($model, $file, $option)
    {
        if (!empty($option['folder_name'])) {
            $action = !empty($option['action']) ? $option['action'] : "Add";
            $id = !empty($option['id']) ? $option['id'] : "";
            $folder_name = !empty($option['folder_name']) ? $option['folder_name'] : null;
            $file_description = !empty($option['file_description']) ? $option['file_description'] : null;
            $file_type = !empty($option['file_type']) ? $option['file_type'] : null;

            if ($action == 'Add') {
                $fileName = $file->getClientOriginalName();
                $filePath = Str::random(10) . '.' . $file->extension();
                $filePath = $file->storeAs($folder_name, $filePath, 'public');
                $fileSize = $this->formatSizeUnits($file->getSize());

                $model->attachments()->create([
                    'file_name' => $fileName,
                    'file_path' => "storage/" . $filePath,
                    'file_size' => $fileSize,
                    'file_description' => $file_description,
                    'file_type' => $file_type
                ]);

                return true;
            }
        }

        return false;
    }

    function send_email($options)
    {
        // local testing
        // php artisan queue:work
        $title = array_key_exists("title", $options) ? $options["title"] : "";
        $system_id = array_key_exists("system_id", $options) ? $options["system_id"] : "";

        $to_name = array_key_exists("to_name", $options) ? $options["to_name"] : "";
        $to_email = array_key_exists("to_email", $options) ? $options["to_email"] : "";

        $from_name = array_key_exists("from_name", $options) ? $options["from_name"] : "Father Saturnino Urios University";
        $sender_name = array_key_exists("sender_name", $options) ? $options["sender_name"] : "";
        $from_email = array_key_exists("from_email", $options) ? $options["from_email"] : "support@fsuudsac.com";
        $link = array_key_exists("link", $options) ? $options["link"] : "";
        $link_name = array_key_exists("link_name", $options) ? $options["link_name"] : "";
        $code = array_key_exists("code", $options) ? $options["code"] : "";

        $exam_schedule = array_key_exists("exam_schedule", $options) ? $options["exam_schedule"] : "";

        $fullname = array_key_exists("fullname", $options) ? $options["fullname"] : "";
        $account = array_key_exists("account", $options) ? $options["account"] : "";
        $password = array_key_exists("password", $options) ? $options["password"] : "";

        $template = array_key_exists("template", $options) ? $options["template"] : "emails.email-template";
        $position = array_key_exists("position", $options) ? $options["position"] : "position";

        $attachment = array_key_exists("attachment", $options) ? $options["attachment"] : [];

        $new_link = '<a href="' . $link . '" target="_blank"
        style="mso-style-priority:100 !important;text-decoration:none;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:roboto, "helvetica neue", helvetica, arial, sans-serif;font-size:18px;color:#333333;border-style:solid;border-color:#FEC300;border-width:10px 20px;display:inline-block;background:#FEC300;border-radius:4px;font-weight:bold;font-style:normal;line-height:22px;width:auto;text-align:center;">' . $link_name . '</a>';

        if ($title && $system_id) {
            $email_template = EmailTemplate::where("title", $title)->where("system_id", $system_id)->first();
            if ($email_template) {
                $subject = $email_template->subject;
                $body = $email_template->body;

                if ($subject) {
                    $subject = str_replace('[user:fullname]', $fullname, $subject); //sample
                }

                if ($body) {

                    if ($to_name) {
                        $body = str_replace('[user:to_name]', $to_name, $body);
                        $body = str_replace('[user:applicant_name]', $to_name, $body);
                    }

                    if ($new_link) {
                        $body = str_replace('[site:set-password-url]', $new_link, $body);
                    }

                    if ($sender_name) {
                        $body = str_replace('[user:sender_name]', $sender_name, $body);
                        $body = str_replace('[user:from_name]', $from_name, $body);
                    }

                    if ($position) {
                        $body = str_replace('[user:position]', $position, $body);
                    }

                    if ($exam_schedule) {
                        $body = str_replace('[user:exam_schedule]', $exam_schedule, $body);
                    }

                    if ($account) {
                        $body = str_replace('[user:account]', $account, $body);
                    }

                    if ($password) {
                        $body = str_replace('[user:password]', $password, $body);
                    }
                }

                // footer signature
                $data_email = [
                    'to_name'       => $to_name,
                    'to_email'      => $to_email,
                    'subject'       => $subject,
                    'from_name'     => $from_name,
                    'from_email'    => $from_email,
                    'template'      => $template,
                    'body_data'     => [
                        "content" => $body,
                    ]
                ];

                if (count($attachment) > 0) {
                    $data_email["attachment"] = $attachment;
                }

                event(new \App\Events\SendEmailEvent($data_email));

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
