<?php

namespace App\Console\Commands;

use App\Models\EmailTemplate;
use App\Models\ProfileContactInformation;
use App\Models\StudentExam;
use Illuminate\Console\Command;

class ExamResultEmailSpam extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:exam_result_email_spam';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending email to applicants taken the entrance exam';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $findExamResult = StudentExam::where('status', "Active")->where('schedule_status', "Approved")->where('exam_status', "Taken")->whereNull('exam_result')->get();

        if ($findExamResult->isNotEmpty()) {
            foreach ($findExamResult as $key => $value) {

                if ($value->date_taken == date('Y-m-d', strtotime('-21 days'))) {
                    $findEmail = ProfileContactInformation::where('profile_id', $value->profile_id)->where('status', 1)->latest()->first();

                    if ($findEmail) {
                        $this->send_email([
                            'title' => "ADMISSION EXAM (SPAM)",
                            'to_name' => $findEmail->fullname,
                            'position' => "FSUU GUIDANCE",
                            'to_email' => $findEmail->email,
                            'sender_name' => "FSUU GUIDANCE",
                            "system_id" => 3,
                        ]);
                    }
                } else  if ($value->date_taken == date('Y-m-d', strtotime('-14 days'))) {
                    $findEmail = ProfileContactInformation::where('profile_id', $value->profile_id)->where('status', 1)->latest()->first();

                    if ($findEmail) {
                        $this->send_email([
                            'title' => "ADMISSION EXAM (SPAM)",
                            'to_name' => $findEmail->fullname,
                            'position' => "FSUU GUIDANCE",
                            'to_email' => $findEmail->email,
                            'sender_name' => "FSUU GUIDANCE",
                            "system_id" => 3,
                        ]);
                    }
                } else  if ($value->date_taken == date('Y-m-d', strtotime('-7 days'))) {
                    $findEmail = ProfileContactInformation::where('profile_id', $value->profile_id)->where('status', 1)->latest()->first();

                    if ($findEmail) {
                        $this->send_email([
                            'title' => "ADMISSION EXAM (SPAM)",
                            'to_name' => $findEmail->fullname,
                            'position' => "FSUU GUIDANCE",
                            'to_email' => $findEmail->email,
                            'sender_name' => "FSUU GUIDANCE",
                            "system_id" => 3,
                        ]);
                    }
                }
            }
        }

        $this->info("Exam Result Email Spam");
    }

    public function send_email($options)
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