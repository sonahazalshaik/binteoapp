@extends('admin.layouts.app')
@section('panel')
    @push('topBar')
        @include('admin.notification.top_bar')
    @endpush
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card bl--5 border--primary">
                <div class="card-body">
                    <p class="text--primary">@lang('If you want to send push notification by the firebase, Your system must be SSL certified')</p>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <form action="#" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('API Key') </label>
                                    <input type="text" class="form-control" placeholder="@lang('API Key')" name="apiKey" value="{{ @gs('firebase_config')->apiKey }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Auth Domain') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Auth Domain')" name="authDomain" value="{{ @gs('firebase_config')->authDomain }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Project Id') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Project Id')" name="projectId" value="{{ @gs('firebase_config')->projectId }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Storage Bucket') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Storage Bucket')" name="storageBucket" value="{{ @gs('firebase_config')->storageBucket }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Messaging Sender Id') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Messaging Sender Id')" name="messagingSenderId" value="{{ @gs('firebase_config')->messagingSenderId }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('App Id') </label>
                                    <input type="text" class="form-control" placeholder="@lang('App Id')" name="appId" value="{{ @gs('firebase_config')->appId }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Measurement Id') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Measurement Id')" name="measurementId" value="{{ @gs('firebase_config')->measurementId }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('VAPID Key') </label>
                                    <input type="text" class="form-control" placeholder="@lang('VAPID Key')" name="vapidKey" value="{{ @gs('firebase_config')->vapidKey }}" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="w-full sm:w-auto h-16 px-12 rounded-[2rem] bg-indigo-600 text-white font-black uppercase tracking-[0.2em] text-[12px] hover:scale-105 transition-all shadow-2xl active:scale-95 ">@lang('Submit')</button>
                    </div>
                </form>
            </div><!-- card end -->
        </div>
    </div>

    <div id="pushNotifyModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Firebase Setup')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs border-b border-slate-100 dark:border-white/5" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active font-black uppercase tracking-widest text-[10px] py-4 px-6 border-b-2 border-transparent" id="steps-tab" data-bs-toggle="tab" data-bs-target="#steps" type="button" role="tab" aria-controls="steps" aria-selected="true">@lang('Steps')</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link font-black uppercase tracking-widest text-[10px] py-4 px-6 border-b-2 border-transparent" id="configs-tab" data-bs-toggle="tab" data-bs-target="#configs" type="button" role="tab" aria-controls="configs" aria-selected="false">@lang('Configs')</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link font-black uppercase tracking-widest text-[10px] py-4 px-6 border-b-2 border-transparent" id="server-tab" data-bs-toggle="tab" data-bs-target="#server" type="button" role="tab" aria-controls="server" aria-selected="false">@lang('Server Key')</button>
                        </li>
                    </ul>
                    <div class="tab-content mt-6" id="myTabContent">
                        <div class="tab-pane fade show active" id="steps" role="tabpanel" aria-labelledby="steps-tab">
                            <div class="table-responsive overflow-hidden rounded-2xl border border-slate-100 dark:border-white/5">
                                <table class="table table-striped mb-0">
                                    <thead class="bg-slate-50 dark:bg-white/[0.02]">
                                        <tr>
                                            <th class="text-[10px] font-black uppercase tracking-widest text-slate-400">@lang('To Do')</th>
                                            <th class="text-[10px] font-black uppercase tracking-widest text-slate-400">@lang('Description')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="font-bold text-slate-700 dark:text-white/70">@lang('Step 1')</td>
                                            <td class="text-slate-500 dark:text-white/40">@lang('Go to your Firebase account and select') <span class="text-indigo-500 font-bold">"@lang('Go to console')</span>" @lang('in the upper-right corner of the page.')</td>
                                        </tr>
                                        <tr>
                                            <td class="font-bold text-slate-700 dark:text-white/70">@lang('Step 2')</td>
                                            <td class="text-slate-500 dark:text-white/40">
                                                @lang('Select Add project and do the following to create your project.')
                                                <br>
                                                <code class="text-indigo-500 font-bold bg-indigo-50 dark:bg-indigo-500/10 px-2 py-1 rounded mt-2 inline-block">
                                                    @lang('Use the name, Enable Google Analytics, Choose a name and the country for Google Analytics, Use the default analytics settings')
                                                </code>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-bold text-slate-700 dark:text-white/70">@lang('Step 3')</td>
                                            <td class="text-slate-500 dark:text-white/40">@lang('Within your Firebase project, select the gear next to Project Overview and choose Project settings.')</td>
                                        </tr>
                                        <tr>
                                            <td class="font-bold text-slate-700 dark:text-white/70">@lang('Step 4')</td>
                                            <td class="text-slate-500 dark:text-white/40">@lang('Next, set up a web app under the General section of your project settings.')</td>
                                        </tr>
                                        <tr>
                                            <td class="font-bold text-slate-700 dark:text-white/70">@lang('Step 5')</td>
                                            <td class="text-slate-500 dark:text-white/40">@lang('Next, go to Cloud Messaging in your Firebase project settings and enable Cloud Messaging API.')</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade mt-3 ms-2 text-center" id="configs" role="tabpanel" aria-labelledby="configs-tab">
                            <img src="{{ getImage('assets/images/firebase/' . 'configs.png') }}" alt="Firebase Config" class="rounded-3xl shadow-2xl mx-auto border border-slate-100 dark:border-white/5">
                        </div>
                        <div class="tab-pane fade mt-3 ms-2 text-center" id="server" role="tabpanel" aria-labelledby="server-tab">
                            <img src="{{ getImage('assets/images/firebase/' . 'server.png') }}" alt="Firebase Server" class="rounded-3xl shadow-2xl mx-auto border border-slate-100 dark:border-white/5">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="h-10 px-6 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-white/60 text-[11px] font-black uppercase tracking-widest hover:bg-slate-200 transition-all" data-bs-dismiss="modal">@lang('Close Operations')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <button type="button" data-bs-target="#pushNotifyModal" data-bs-toggle="modal" class="h-10 px-6 rounded-full bg-cyan-600 text-white text-[11px] font-black uppercase tracking-widest hover:scale-105 hover:bg-cyan-700 transition-all shadow-lg shadow-cyan-500/20 flex items-center gap-2">
        <span class="material-symbols-rounded text-lg">help</span> @lang('Help Center')
    </button>
@endpush

