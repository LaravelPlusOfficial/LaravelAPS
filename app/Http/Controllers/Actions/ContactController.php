<?php

namespace App\Http\Controllers\Actions;

use App\Rules\Recaptcha;
use App\Mail\ContactHelpDesk;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Services\Seo\Contract\SeoContract;

class ContactController extends Controller
{

    /**
     * @var SeoContract
     */
    protected $seo;

    public function __construct(SeoContract $seo)
    {
        $this->seo = $seo;
    }

    public function show()
    {
        return $this->seoView(view('app.pages.contact'));
    }

    public function store()
    {

        $data = request()->validate([
            'name'               => 'required|max:255',
            'email'              => 'required|email|max:255',
            'inquiry'            => 'required|max:10000',
            'gRecaptchaResponse' => ['required', (new Recaptcha())]
        ]);

        Mail::send(new ContactHelpDesk($data));

        session()->flash('success', 'Inquiry posted');

        return $this->seoView(view('app.pages.contact'));

    }

    protected function seoView($view)
    {
        return $this->seo->setMetaTags($view, [
            'type'  => 'page',
            'metas' => [
                'seo_title'       => 'Contact Us',
                'seo_description' => 'Have questions? Shoot us an Email'
            ]
        ]);
    }

}
