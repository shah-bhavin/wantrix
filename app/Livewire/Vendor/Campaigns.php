<?php

namespace App\Livewire\Vendor;

use App\Enums\CampaignStatus;
use App\Models\Campaign;
use App\Models\WhatsappAccount;
use App\Support\UsageLimit;
use Livewire\Component;

class Campaigns extends Component
{
    public bool $showCreateModal = false;

    public string $name = '';

    public string $group_id = '';

    public string $message_type = 'template';

    public string $whatsapp_account_id = '';

    public ?int $whatsapp_template_id = null;

    public string $text_content_mode = 'direct';

    public string $template_id = '';

    public string $message_body = '';

    public ?int $media_library_id = null;

    public ?string $scheduled_at = null;

    public int $message_delay_seconds = 0;


    public function updatedMessageType(): void
    {
        $this->reset([
            'whatsapp_template_id',
            'template_id',
            'message_body',
            'media_library_id',
        ]);

        $this->text_content_mode = 'direct';
    }


    public function updatedWhatsappAccountId(): void
    {
        $this->whatsapp_template_id = null;
    }


    public function updatedTextContentMode(): void
    {
        $this->reset([
            'template_id',
            'message_body',
        ]);
    }


    public function createCampaign(): void
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],

            'group_id' => ['required', 'exists:groups,id'],

            'whatsapp_account_id' => [
                'required',
                'exists:whatsapp_accounts,id',
            ],

            'message_type' => [
                'required',
                'in:template,text,image,video,document,audio',
            ],

            'scheduled_at' => [
                'nullable',
                'date',
                'after:now',
            ],

            'message_delay_seconds' => [
                'required',
                'integer',
                'min:0',
                'max:3600',
            ],
        ];


        /*
        |--------------------------------------------------------------------------
        | Message Type Specific Validation
        |--------------------------------------------------------------------------
        */

        if ($this->message_type === 'template') {

            $rules['whatsapp_template_id'] = [
                'required',
                'exists:whatsapp_templates,id',
            ];
        }


        if ($this->message_type === 'text') {

            $rules['text_content_mode'] = [
                'required',
                'in:direct,template',
            ];

            if ($this->text_content_mode === 'direct') {

                $rules['message_body'] = [
                    'required',
                    'string',
                ];
            }

            if ($this->text_content_mode === 'template') {

                $rules['template_id'] = [
                    'required',
                    'exists:templates,id',
                ];
            }
        }


        if (in_array($this->message_type, [
            'image',
            'video',
            'document',
            'audio',
        ])) {

            $rules['media_library_id'] = [
                'required',
                'exists:media_libraries,id',
            ];
        }


        $this->validate($rules);


        $vendor = auth()->user()->vendor;


        /*
        |--------------------------------------------------------------------------
        | Security / Ownership Validation
        |--------------------------------------------------------------------------
        */

        $group = $vendor->groups()
            ->whereKey($this->group_id)
            ->first();

        if (! $group) {

            $this->addError(
                'group_id',
                'Invalid group selected.'
            );

            return;
        }


        $account = $vendor->whatsappAccounts()
            ->whereKey($this->whatsapp_account_id)
            ->where('is_active', true)
            ->first();

        if (! $account) {

            $this->addError(
                'whatsapp_account_id',
                'Invalid or inactive WhatsApp account selected.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Meta Template Ownership Validation
        |--------------------------------------------------------------------------
        */

        if (
            $this->message_type === 'template'
            && $this->whatsapp_template_id
        ) {

            $metaTemplateExists = $vendor
                ->whatsappTemplates()
                ->whereKey($this->whatsapp_template_id)
                ->where(
                    'whatsapp_account_id',
                    $account->id
                )
                ->where('status', 'approved')
                ->exists();

            if (! $metaTemplateExists) {

                $this->addError(
                    'whatsapp_template_id',
                    'Please select an approved template for the selected WhatsApp account.'
                );

                return;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Local Template Ownership Validation
        |--------------------------------------------------------------------------
        */

        if (
            $this->message_type === 'text'
            && $this->text_content_mode === 'template'
            && $this->template_id
        ) {

            $templateExists = $vendor
                ->templates()
                ->whereKey($this->template_id)
                ->exists();

            if (! $templateExists) {

                $this->addError(
                    'template_id',
                    'Invalid local template selected.'
                );

                return;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Media Ownership and Type Validation
        |--------------------------------------------------------------------------
        */

        if (
            in_array($this->message_type, [
                'image',
                'video',
                'document',
                'audio',
            ])
            && $this->media_library_id
        ) {

            $media = $vendor
                ->mediaLibraries()
                ->whereKey($this->media_library_id)
                ->where('uploaded_to_meta', true)
                ->first();

            if (! $media) {

                $this->addError(
                    'media_library_id',
                    'Invalid media selected.'
                );

                return;
            }

            if ($media->type->value !== $this->message_type) {

                $this->addError(
                    'media_library_id',
                    'Selected media type does not match the campaign message type.'
                );

                return;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Usage Limit
        |--------------------------------------------------------------------------
        */

        if (! UsageLimit::canCreateCampaign($vendor)) {

            session()->flash(
                'error',
                'Campaign limit reached. Upgrade your plan.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Create Campaign
        |--------------------------------------------------------------------------
        */

        Campaign::create([

            'vendor_id' => $vendor->id,

            'group_id' => $group->id,

            /*
             * Local template is used only for:
             * Text -> Local Template
             */
            'template_id' => (
                $this->message_type === 'text'
                && $this->text_content_mode === 'template'
            )
                ? $this->template_id
                : null,

            /*
             * Meta approved template
             */
            'whatsapp_template_id' => (
                $this->message_type === 'template'
            )
                ? $this->whatsapp_template_id
                : null,

            'message_type' => $this->message_type,

            /*
             * Direct text message
             */
            'message_body' => (
                $this->message_type === 'text'
                && $this->text_content_mode === 'direct'
            )
                ? $this->message_body
                : null,

            /*
             * Media campaign
             */
            'media_library_id' => in_array(
                $this->message_type,
                [
                    'image',
                    'video',
                    'document',
                    'audio',
                ]
            )
                ? $this->media_library_id
                : null,

            'whatsapp_account_id' => $account->id,

            'name' => $this->name,

            'status' => $this->scheduled_at
                ? CampaignStatus::SCHEDULED
                : CampaignStatus::DRAFT,

            'scheduled_at' => $this->scheduled_at,

            'message_delay_seconds' =>
                $this->message_delay_seconds,

        ]);


        /*
        |--------------------------------------------------------------------------
        | Reset Form
        |--------------------------------------------------------------------------
        */

        $this->reset([
            'name',
            'group_id',
            'whatsapp_account_id',
            'whatsapp_template_id',
            'template_id',
            'message_body',
            'media_library_id',
            'scheduled_at',
            'message_delay_seconds',
        ]);

        $this->message_type = 'template';

        $this->text_content_mode = 'direct';

        $this->showCreateModal = false;


        session()->flash(
            'success',
            'Campaign created successfully.'
        );
    }


    public function render()
    {
        $vendor = auth()->user()->vendor;


        $campaigns = $vendor
            ->campaigns()
            ->with([
                'group',
                'template',
                'whatsappAccount',
            ])
            ->latest()
            ->get();


        $groups = $vendor
            ->groups()
            ->orderBy('name')
            ->get();


        $templates = $vendor
            ->templates()
            ->orderBy('name')
            ->get();


        $whatsappAccounts = $vendor
            ->whatsappAccounts()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Approved Meta Templates
        |--------------------------------------------------------------------------
        |
        | Only show templates belonging to the selected WhatsApp account.
        |
        */

        $whatsappTemplates = collect();

        if ($this->whatsapp_account_id) {

            $whatsappTemplates = $vendor
                ->whatsappTemplates()
                ->where(
                    'whatsapp_account_id',
                    $this->whatsapp_account_id
                )
                ->where('status', 'approved')
                ->orderBy('name')
                ->get();
        }


        $media = $vendor
            ->mediaLibraries()
            ->where('uploaded_to_meta', true)
            ->orderBy('original_name')
            ->get();


        return view(
            'livewire.vendor.campaigns',
            compact(
                'campaigns',
                'groups',
                'templates',
                'whatsappAccounts',
                'whatsappTemplates',
                'media',
            )
        )->layout('layouts.vendor');
    }
}