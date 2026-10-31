<?php

namespace App\Services\Admin;

use App\Models\OttPlan;
use App\Models\OttSubscription;
use App\Models\Plan;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class PlanService
{
    public function authorize(string $ability): void
    {
        abort_if(Gate::denies($ability), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function getAllPlans(int $perPage = 15)
    {
        return Plan::with('user')->paginate($perPage);
    }

    public function getManagePlans(int $perPage = 15)
    {
        return Plan::searchable(['name', 'user:username'])
            ->with('user')
            ->withCount('videos')
            ->withCount('playlists')
            ->paginate($perPage);
    }

    public function createPlan(array $data): Plan
    {
        $plan = new Plan();
        $plan->name = $data['name'];
        $plan->description = $data['description'] ?? null;
        $plan->price = $data['price'];
        $plan->duration = $data['duration'];
        $plan->video_access = $data['video_access'] ?? 0;
        $plan->contact_access = $data['contact_access'] ?? 0;
        $plan->is_featured_plan = $data['is_featured_plan'] ?? 0;
        $plan->status = $data['status'] ?? 1;
        $plan->save();

        return $plan;
    }

    public function updatePlan(Plan $plan, array $data): Plan
    {
        $plan->name = $data['name'] ?? $plan->name;
        $plan->description = $data['description'] ?? $plan->description;
        $plan->price = $data['price'] ?? $plan->price;
        $plan->duration = $data['duration'] ?? $plan->duration;
        $plan->video_access = $data['video_access'] ?? $plan->video_access;
        $plan->contact_access = $data['contact_access'] ?? $plan->contact_access;
        $plan->is_featured_plan = $data['is_featured_plan'] ?? $plan->is_featured_plan;
        $plan->save();

        return $plan;
    }

    public function updatePlanBasic(int $id, array $data): Plan
    {
        $plan = Plan::findOrFail($id);
        $plan->name = $data['name'] ?? $plan->name;
        $plan->slug = $data['slug'] ?? $plan->slug;
        $plan->price = $data['price'] ?? $plan->price;
        $plan->save();

        return $plan;
    }

    public function togglePlanStatus(Plan $plan): bool
    {
        $plan->status = !$plan->status;
        $plan->save();

        return $plan->status;
    }

    public function changePlanStatus(int $id): mixed
    {
        return Plan::changeStatus($id);
    }

    public function deletePlan(Plan $plan): void
    {
        $plan->delete();
    }

    public function getPlanVideos(int $planId, int $perPage = 15)
    {
        $plan = Plan::findOrFail($planId);
        return $plan->videos()->searchable(['title', 'name'])->paginate($perPage);
    }

    public function getPlanPlaylists(int $planId, int $perPage = 15)
    {
        $plan = Plan::findOrFail($planId);
        return $plan->playlists()->searchable(['name'])->paginate($perPage);
    }

    public function getAllOttPlans(int $perPage = 20)
    {
        return OttPlan::latest()->paginate($perPage);
    }

    public function createOttPlan(array $data): OttPlan
    {
        $plan = new OttPlan();
        $plan->name = $data['name'];
        $plan->description = $data['description'] ?? null;
        $plan->price = $data['price'];
        $plan->duration = $data['duration'];
        $plan->ott_access = $data['ott_access'] ?? 0;
        $plan->status = $data['status'] ?? 1;
        $plan->save();

        return $plan;
    }

    public function updateOttPlan(int $id, array $data): OttPlan
    {
        $plan = OttPlan::findOrFail($id);
        $plan->name = $data['name'] ?? $plan->name;
        $plan->description = $data['description'] ?? $plan->description;
        $plan->price = $data['price'] ?? $plan->price;
        $plan->duration = $data['duration'] ?? $plan->duration;
        $plan->ott_access = $data['ott_access'] ?? $plan->ott_access;
        $plan->save();

        return $plan;
    }

    public function toggleOttPlanStatus(int $id): bool
    {
        $plan = OttPlan::findOrFail($id);
        $plan->status = !$plan->status;
        $plan->save();

        return $plan->status;
    }

    public function deleteOttPlan(int $id): void
    {
        OttPlan::findOrFail($id)->delete();
    }

    public function getOttSubscriptions(int $perPage = 20)
    {
        return OttSubscription::with(['user', 'ottPlan'])
            ->searchable(['plan_name', 'user:username,email,firstname,lastname'])
            ->latest()
            ->paginate($perPage);
    }
}
