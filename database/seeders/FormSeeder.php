<?php

namespace Database\Seeders;

use App\Models\Form;
use Illuminate\Database\Seeder;

class FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Form::updateOrCreate(['act' => 'advertiser'], [
            'form_data' => [
                'business_name' => [
                    'name' => 'Business Name',
                    'type' => 'text',
                    'is_required' => 'required',
                    'label' => 'business_name',
                    'width' => '12',
                    'instruction' => 'Enter the official name of your company or brand.'
                ],
                'website' => [
                    'name' => 'Website / URL',
                    'type' => 'url',
                    'is_required' => 'required',
                    'label' => 'website',
                    'width' => '12',
                    'instruction' => 'Provide a link to your primary business website or social landing page.'
                ],
                'brief_description' => [
                    'name' => 'Brief Description',
                    'type' => 'textarea',
                    'is_required' => 'required',
                    'label' => 'brief_description',
                    'width' => '12',
                    'instruction' => 'Briefly describe your products or services and what you intend to promote.'
                ],
            ]
        ]);
    }
}
