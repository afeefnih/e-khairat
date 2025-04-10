<?php

namespace App\Livewire;

use Livewire\Component;

class Terms extends Component
{
    public $currentSection = 'all';
    public $language = 'ms'; // Default to Malay
    public $searchQuery = '';
    public $showMobileMenu = false;

    public $sections = [
        'tanggungan' => [
            'id' => 1,
            'title' => 'Tanggungan Ahli',
            'icon' => 'users'
        ],
        'sumbangan' => [
            'id' => 2,
            'title' => 'Sumbangan Keahlian',
            'icon' => 'cash'
        ],
        'nota' => [
            'id' => 3,
            'title' => 'Nota Penting',
            'icon' => 'information-circle'
        ],
        'hak' => [
            'id' => 4,
            'title' => 'Hak dan Tanggungjawab Ahli',
            'icon' => 'shield-check'
        ],
        'perubahan' => [
            'id' => 5,
            'title' => 'Perubahan Syarat',
            'icon' => 'refresh'
        ],
    ];

    public function setSection($section)
    {
        $this->currentSection = $section;
        $this->showMobileMenu = false;
    }

    public function toggleLanguage()
    {
        $this->language = $this->language === 'ms' ? 'en' : 'ms';
    }

    public function toggleMobileMenu()
    {
        $this->showMobileMenu = !$this->showMobileMenu;
    }

    public function render()
    {
        return view('livewire.terms');
    }
}
