<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\WithSweetAlert;
use App\Models\Fund;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Funds extends Component
{
    use WithSweetAlert;
    use WithPagination;
    use WithFileUploads;

    public $search = '';

    public string $filterCategory = '';

    public string $filterCompany = '';

    public $showModal = false;

    public $showDeleteModal = false;

    public $editMode = false;

    public $fundId;

    public $name = '';

    public $slug = '';

    public $company = '';

    public $company_short = '';

    public $category = 'Diversifié';

    public $country = 'Bénin';

    public $origin_nav = '';

    public $current_nav = '';

    public $variation_origin = '';

    public $vl_date = '';

    public $source = '';

    public $notes = '';

    public $flyer;

    public $flyer_path = '';

    public $is_active = true;

    public $display_order = 0;

    protected $paginationTheme = 'tailwind';

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:funds,slug,'.($this->fundId ?? 'NULL'),
            'company' => 'required|string|max:255',
            'company_short' => 'nullable|string|max:80',
            'category' => 'required|string|max:80',
            'country' => 'nullable|string|max:80',
            'origin_nav' => 'nullable|numeric|min:0',
            'current_nav' => 'nullable|numeric|min:0',
            'variation_origin' => 'nullable|numeric',
            'vl_date' => 'nullable|date',
            'source' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:2000',
            'flyer' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du fonds est obligatoire.',
            'company.required' => 'La SGO est obligatoire.',
            'category.required' => 'La catégorie est obligatoire.',
            'flyer.mimes' => 'Le flyer doit être un PDF ou une image.',
            'flyer.max' => 'Le flyer ne doit pas dépasser 5 Mo.',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterCategory()
    {
        $this->resetPage();
    }

    public function updatingFilterCompany()
    {
        $this->resetPage();
    }

    public function updatedName(): void
    {
        if (! $this->editMode || empty($this->slug)) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function openModal(): void
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function edit(int $id): void
    {
        $fund = Fund::findOrFail($id);
        $this->fundId = $fund->id;
        $this->name = $fund->name;
        $this->slug = $fund->slug;
        $this->company = $fund->company;
        $this->company_short = $fund->company_short ?? '';
        $this->category = $fund->category;
        $this->country = $fund->country ?? 'Bénin';
        $this->origin_nav = $fund->origin_nav !== null ? (string) $fund->origin_nav : '';
        $this->current_nav = $fund->current_nav !== null ? (string) $fund->current_nav : '';
        $this->variation_origin = $fund->variation_origin !== null ? (string) $fund->variation_origin : '';
        $this->vl_date = $fund->vl_date?->format('Y-m-d') ?? '';
        $this->source = $fund->source ?? '';
        $this->notes = $fund->notes ?? '';
        $this->flyer_path = $fund->flyer ?? '';
        $this->is_active = (bool) $fund->is_active;
        $this->display_order = (int) $fund->display_order;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $flyerPath = $this->flyer_path;
        if ($this->flyer) {
            $flyerPath = $this->flyer->store('funds/flyers', 'public');
        }

        $data = [
            'name' => $this->name,
            'slug' => $this->slug ?: Str::slug($this->name),
            'company' => $this->company,
            'company_short' => $this->company_short ?: null,
            'category' => $this->category,
            'country' => $this->country ?: 'Bénin',
            'origin_nav' => $this->toDecimal($this->origin_nav),
            'current_nav' => $this->toDecimal($this->current_nav),
            'variation_origin' => $this->toDecimal($this->variation_origin),
            'vl_date' => $this->vl_date ?: null,
            'source' => $this->source ?: Fund::DEFAULT_SOURCE,
            'notes' => $this->notes ?: null,
            'flyer' => $flyerPath ?: null,
            'is_active' => (bool) $this->is_active,
            'display_order' => (int) ($this->display_order ?? 0),
        ];

        if ($this->editMode) {
            $fund = Fund::findOrFail($this->fundId);
            if (! $this->flyer) {
                unset($data['flyer']);
            }
            $fund->update($data);
            $this->swalSuccess('FCP modifié avec succès.');
        } else {
            Fund::create($data);
            $this->swalSuccess('FCP créé avec succès.');
        }

        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->fundId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        Fund::findOrFail($this->fundId)->delete();
        $this->swalSuccess('FCP supprimé.');
        $this->showDeleteModal = false;
        $this->fundId = null;
    }

    public function toggleActive(int $id): void
    {
        $fund = Fund::findOrFail($id);
        $fund->update(['is_active' => ! $fund->is_active]);
        $this->swalSuccess($fund->is_active ? 'FCP visible sur le site.' : 'FCP masqué.');
    }

    private function toDecimal(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        $s = str_replace([' ', "\xc2\xa0"], '', (string) $value);
        $s = str_replace(',', '.', $s);

        return is_numeric($s) ? (float) $s : null;
    }

    private function resetForm(): void
    {
        $this->fundId = null;
        $this->name = '';
        $this->slug = '';
        $this->company = '';
        $this->company_short = '';
        $this->category = 'Diversifié';
        $this->country = 'Bénin';
        $this->origin_nav = '';
        $this->current_nav = '';
        $this->variation_origin = '';
        $this->vl_date = Fund::DEFAULT_VL_DATE;
        $this->source = Fund::DEFAULT_SOURCE;
        $this->notes = '';
        $this->flyer = null;
        $this->flyer_path = '';
        $this->is_active = true;
        $this->display_order = 0;
    }

    public function render()
    {
        $funds = Fund::query()
            ->when($this->search, function ($query) {
                $like = '%'.$this->search.'%';
                $query->where(function ($q) use ($like) {
                    $q->where('name', 'like', $like)
                        ->orWhere('company', 'like', $like)
                        ->orWhere('company_short', 'like', $like);
                });
            })
            ->when($this->filterCategory !== '', fn ($q) => $q->where('category', $this->filterCategory))
            ->when($this->filterCompany !== '', fn ($q) => $q->where('company', $this->filterCompany))
            ->orderBy('display_order')
            ->orderBy('name')
            ->paginate(20);

        return view('livewire.admin.funds', [
            'funds' => $funds,
            'companies' => Fund::query()->orderBy('company')->distinct()->pluck('company'),
            'categories' => array_values(Fund::CATEGORIES),
        ])
            ->extends('layouts.admin', ['title' => 'FCP / OPCVM'])
            ->section('content');
    }
}
