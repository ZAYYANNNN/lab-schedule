<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Borrowing extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'borrowings';

    protected $fillable = [
        'user_id',
        'nama_peminjam',
        'nomor_identitas',
        'lab_id',
        'asset_id',
        'borrow_date',
        'borrow_time',
        'return_date',
        'return_time',
        'actual_return_datetime',
        'status_id',
        'notes',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'return_date' => 'date',
        'actual_return_datetime' => 'datetime',
    ];

    public function asset()
    {
        return $this->belongsTo(AssetLab::class, 'asset_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lab()
    {
        return $this->belongsTo(Lab::class, 'lab_id');
    }

    public function status()
    {
        return $this->belongsTo(BorrowingStatus::class, 'status_id');
    }

    /**
     * Check if borrowing is overdue
     */
    public function isOverdue()
    {
        // Don't mark as overdue if status is returned or rejected
        if ($this->status && in_array($this->status->slug, ['returned', 'rejected'])) {
            return false;
        }

        $returnDate = $this->return_date instanceof \Carbon\Carbon
            ? $this->return_date->format('Y-m-d')
            : $this->return_date;

        $returnDateTime = \Carbon\Carbon::parse($returnDate . ' ' . ($this->return_time ?? '23:59:59'));
        return now()->greaterThan($returnDateTime);
    }

    /**
     * Get late duration in human readable format
     */
    public function getLateDuration()
    {
        if (!$this->actual_return_datetime) {
            return null;
        }

        $returnDate = $this->return_date instanceof \Carbon\Carbon
            ? $this->return_date->format('Y-m-d')
            : $this->return_date;

        $expectedReturn = \Carbon\Carbon::parse($returnDate . ' ' . ($this->return_time ?? '23:59:59'));

        if ($this->actual_return_datetime->lessThanOrEqualTo($expectedReturn)) {
            return null;
        }

        $diff = $expectedReturn->diff($this->actual_return_datetime);

        $parts = [];
        if ($diff->d > 0) {
            $parts[] = $diff->d . ' hari';
        }
        if ($diff->h > 0) {
            $parts[] = $diff->h . ' jam';
        }
        if ($diff->i > 0 && $diff->d == 0) {
            $parts[] = $diff->i . ' menit';
        }

        return !empty($parts) ? implode(' ', $parts) : null;
    }
}