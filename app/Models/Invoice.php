<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'invoice_number',
        'status',
        'notes',
        'tax_rate',
        'due_date',
    ];

    protected $casts = [
        'tax_rate' => 'decimal:2',
        'due_date' => 'date',
    ];

    // ── Relationships ──────────────────────────────────────────────────

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    // ── Computed Totals ────────────────────────────────────────────────

    /**
     * Sum of all line-item subtotals.
     */
    public function getSubtotalAttribute(): float
    {
        return (float) $this->items->sum('subtotal');
    }

    /**
     * Tax amount = subtotal × (tax_rate / 100).
     */
    public function getTaxAmountAttribute(): float
    {
        return round($this->subtotal * ($this->tax_rate / 100), 2);
    }

    /**
     * Grand total = subtotal + tax.
     */
    public function getTotalAttribute(): float
    {
        return round($this->subtotal + $this->tax_amount, 2);
    }

    // ── Status helpers ─────────────────────────────────────────────────

    public static function statusList(): array
    {
        return ['draft', 'sent', 'paid', 'cancelled'];
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'paid'      => 'emerald',
            'sent'      => 'cyan',
            'cancelled' => 'rose',
            default     => 'amber',   // draft
        };
    }

    // ── Auto-generate invoice number ───────────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (Invoice $invoice) {
            if (empty($invoice->invoice_number)) {
                $last = static::max('id') ?? 0;
                $invoice->invoice_number = 'INV-' . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
