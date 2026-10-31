@extends('admin.layouts.app')
@section('panel')
@push('topBar')
  @include('admin.notification.top_bar')
@endpush
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label>@lang('Sms Send Method')</label>
                            <select name="sms_method" class="select2 form-control"  data-minimum-results-for-search="-1">
                                <option value="clickatell" @if(@gs('sms_config')->name == 'clickatell') selected @endif>@lang('Clickatell')</option>
                                <option value="infobip" @if(@gs('sms_config')->name == 'infobip') selected @endif>@lang('Infobip')</option>
                                <option value="messageBird" @if(@gs('sms_config')->name == 'messageBird') selected @endif>@lang('Message Bird')</option>
                                <option value="nexmo" @if(@gs('sms_config')->name == 'nexmo') selected @endif>@lang('Nexmo')</option>
                                <option value="smsBroadcast" @if(@gs('sms_config')->name == 'smsBroadcast') selected @endif>@lang('Sms Broadcast')</option>
                                <option value="twilio" @if(@gs('sms_config')->name == 'twilio') selected @endif>@lang('Twilio')</option>
                                <option value="textMagic" @if(@gs('sms_config')->name == 'textMagic') selected @endif>@lang('Text Magic')</option>
                                <option value="custom" @if(@gs('sms_config')->name == 'custom') selected @endif>@lang('Custom API')</option>
                            </select>
                        </div>
                        <div class="row mt-4 d-none configForm" id="clickatell">
                            <div class="col-md-12">
                                <h6 class="mb-2">@lang('Clickatell Configuration')</h6>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('API Key') </label>
                                    <input type="text" class="form-control" placeholder="@lang('API Key')" name="clickatell_api_key" value="{{ @gs('sms_config')->clickatell->api_key }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4 d-none configForm" id="infobip">
                            <div class="col-md-12">
                                <h6 class="mb-2">@lang('Infobip Configuration')</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Username') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Username')" name="infobip_username" value="{{ @gs('sms_config')->infobip->username }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Password') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Password')" name="infobip_password" value="{{ @gs('sms_config')->infobip->password }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4 d-none configForm" id="messageBird">
                            <div class="col-md-12">
                                <h6 class="mb-2">@lang('Message Bird Configuration')</h6>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('API Key') </label>
                                    <input type="text" class="form-control" placeholder="@lang('API Key')" name="message_bird_api_key" value="{{ @gs('sms_config')->message_bird->api_key }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4 d-none configForm" id="nexmo">
                            <div class="col-md-12">
                                <h6 class="mb-2">@lang('Nexmo Configuration')</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('API Key') </label>
                                    <input type="text" class="form-control" placeholder="@lang('API Key')" name="nexmo_api_key" value="{{ @gs('sms_config')->nexmo->api_key }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('API Secret') </label>
                                    <input type="text" class="form-control" placeholder="@lang('API Secret')" name="nexmo_api_secret" value="{{ @gs('sms_config')->nexmo->api_secret }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4 d-none configForm" id="smsBroadcast">
                            <div class="col-md-12">
                                <h6 class="mb-2">@lang('Sms Broadcast Configuration')</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Username') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Username')" name="sms_broadcast_username" value="{{ @gs('sms_config')->sms_broadcast->username }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Password') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Password')" name="sms_broadcast_password" value="{{ @gs('sms_config')->sms_broadcast->password }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4 d-none configForm" id="twilio">
                            <div class="col-md-12">
                                <h6 class="mb-2">@lang('Twilio Configuration')</h6>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Account SID') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Account SID')" name="account_sid" value="{{ @gs('sms_config')->twilio->account_sid }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Auth Token') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Auth Token')" name="auth_token" value="{{ @gs('sms_config')->twilio->auth_token }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('From Number') </label>
                                    <input type="text" class="form-control" placeholder="@lang('From Number')" name="from" value="{{ @gs('sms_config')->twilio->from }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4 d-none configForm" id="textMagic">
                            <div class="col-md-12">
                                <h6 class="mb-2">@lang('Text Magic Configuration')</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Username') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Username')" name="text_magic_username" value="{{ @gs('sms_config')->text_magic->username }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Apiv2 Key') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Apiv2 Key')" name="apiv2_key" value="{{ @gs('sms_config')->text_magic->apiv2_key }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4 d-none configForm" id="custom">
                            <div class="col-md-12">
                                <h6 class="mb-2">@lang('Custom API')</h6>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('API URL') </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <select name="custom_api_method" class="method-select">
                                                <option value="get">@lang('GET')</option>
                                                <option value="post">@lang('POST')</option>
                                            </select>
                                        </span>
                                        <input type="text" class="form-control" name="custom_api_url" value="{{ @gs('sms_config')->custom->url }}" placeholder="@lang('API URL')">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive table-responsive--sm mb-3">
                                        <table class=" table align-items-center table--light">
                                            <thead><tr class="bg-slate-50 dark:bg-white/[0.02]">
                                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Short Code') </th>
                                                    <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-white/30 whitespace-nowrap">@lang('Description')</th>
                                                </tr>
                                            </thead>
                                            {{-- blade-formatter-disable --}}
                                            <tbody class="list">
                                                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                                    <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70"><span class="short-codes">@{{message}}</span></td>
                                                    <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">@lang('Message')</td>
                                                </tr>
                                                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                                    <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70"><span class="short-codes">@{{number}}</span></td>
                                                    <td class="px-6 py-4 text-[11px] font-bold text-slate-700 dark:text-white/70">@lang('Number')</td>
                                                </tr>
                                            </tbody>
                                            {{-- blade-formatter-enable --}}
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border--dark mb-3">
                                        <div class="card-header bg--dark d-flex justify-content-between">
                                            <h5 class="text-white">@lang('Headers')</h5>
                                            <button type="button" class="btn btn-sm btn-outline-light float-right addHeader"><i class="la la-fw la-plus"></i>@lang('Add') </button>
                                        </div>
                                        <div class="card-body">
                                            <div class="headerFields">
                                                @for($i = 0; $i < count(gs('sms_config')->custom->headers->name); $i++)
                                                    <div class="row mt-3">
                                                        <div class="col-md-5">
                                                            <input type="text" name="custom_header_name[]" class="form-control" value="{{ @gs('sms_config')->custom->headers->name[$i] }}" placeholder="@lang('Headers Name')">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" name="custom_header_value[]" class="form-control" value="{{ @gs('sms_config')->custom->headers->value[$i] }}" placeholder="@lang('Headers Value')">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="btn btn--danger btn-block removeHeader h-100"><i class="las la-times"></i></button>
                                                        </div>
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border--dark mb-3">
                                        <div class="card-header bg--dark d-flex justify-content-between">
                                            <h5 class="text-white">@lang('Body')</h5>
                                            <button type="button" class="btn btn-sm btn-outline-light float-right addBody"><i class="la la-fw la-plus"></i>@lang('Add') </button>
                                        </div>
                                        <div class="card-body">
                                            <div class="bodyFields">
                                                @for($i = 0; $i < count(gs('sms_config')->custom->body->name); $i++)
                                                    <div class="row mt-3">
                                                        <div class="col-md-5">
                                                            <input type="text" name="custom_body_name[]" class="form-control" value="{{ @gs('sms_config')->custom->body->name[$i] }}" placeholder="@lang('Body Name')">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" name="custom_body_value[]" value="{{ @gs('sms_config')->custom->body->value[$i] }}" class="form-control" placeholder="@lang('Body Value')">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="btn btn--danger btn-block removeBody h-100"><i class="las la-times"></i></button>
                                                        </div>
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="w-full sm:w-auto h-16 px-12 rounded-[2rem] bg-indigo-600 text-white font-black uppercase tracking-[0.2em] text-[12px] hover:scale-105 transition-all shadow-2xl active:scale-95 ">@lang('Submit')</button>
                    </div>
                </form>
            </div><!-- card end -->
        </div>


    </div>


    {{-- TEST SMS MODAL --}}
    <div id="testSMSModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-b border-slate-100 dark:border-white/5 p-6">
                    <h5 class="modal-title text-xl font-black uppercase tracking-tighter ">@lang('Test SMS Setup')</h5>
                    <button type="button" class="h-10 w-10 rounded-full flex items-center justify-center bg-slate-50 dark:bg-white/5 text-slate-400 hover:text-rose-500 transition-colors" data-bs-dismiss="modal" aria-label="Close">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                </div>
                <form action="{{ route('admin.setting.notification.sms.test') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body p-8">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-0">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1 mb-2 block">@lang('Target Mobile Number')</label>
                                    <input type="text" name="mobile" class="w-full h-14 rounded-2xl bg-slate-50 dark:bg-white/5 border-slate-200 dark:border-white/10 px-6 text-sm font-bold text-slate-700 dark:text-white outline-none focus:ring-4 focus:ring-orange-500/10 transition-all" placeholder="@lang('e.g. +1234567890')">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-t border-slate-100 dark:border-white/5 p-6">
                        <button type="submit" class="w-full h-16 rounded-2xl bg-orange-600 text-white font-black uppercase tracking-[0.2em] text-[12px] hover:scale-105 transition-all shadow-2xl active:scale-95 flex items-center justify-center gap-3">
                            <span class="material-symbols-rounded">send</span>
                            @lang('Submit')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')
    <button type="button" data-bs-target="#testSMSModal" data-bs-toggle="modal" class="h-10 px-6 rounded-full bg-orange-600 text-white text-[11px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-lg shadow-orange-500/20 flex items-center gap-2">
        <span class="material-symbols-rounded text-lg">broadcast_on_home</span> @lang('Send Test SMS')
    </button>
@endpush


@push('style')
<style>
    .method-select{
        padding: 2px 7px;
    }
</style>
@endpush


@push('script')
    <script>
        (function ($) {
            "use strict";



            var method = '{{ @gs('sms_config')->name }}';

            if (!method) {
                method = 'clickatell';
            }

            smsMethod(method);
            $('select[name=sms_method]').on('change', function() {
                var method = $(this).val();
                smsMethod(method);
            });

            function smsMethod(method){
                $('.configForm').addClass('d-none');
                if(method != 'php') {
                    $(`#${method}`).removeClass('d-none');
                }
            }

            $('.addHeader').on('click',function(){
                var html = `
                    <div class="row mt-3">
                        <div class="col-md-5">
                            <input type="text" name="custom_header_name[]" class="form-control" placeholder="@lang('Headers Name')">
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="custom_header_value[]" class="form-control" placeholder="@lang('Headers Value')">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn--danger btn-block removeHeader h-100"><i class="las la-times"></i></button>
                        </div>
                    </div>
                `;
                $('.headerFields').append(html);

            })
            $(document).on('click','.removeHeader',function(){
                $(this).closest('.row').remove();
            })

            $('.addBody').on('click',function(){
                var html = `
                    <div class="row mt-3">
                        <div class="col-md-5">
                            <input type="text" name="custom_body_name[]" class="form-control" placeholder="@lang('Body Name')">
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="custom_body_value[]" class="form-control" placeholder="@lang('Body Value')">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn--danger btn-block removeBody h-100"><i class="las la-times"></i></button>
                        </div>
                    </div>
                `;
                $('.bodyFields').append(html);

            })
            $(document).on('click','.removeBody',function(){
                $(this).closest('.row').remove();
            })

            $('select[name=custom_api_method]').val('{{ @gs('sms_config')->custom->method }}');

        })(jQuery);

    </script>
@endpush


