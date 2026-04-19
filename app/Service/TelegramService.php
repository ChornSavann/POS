<?php

namespace App\Service;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TelegramService
{
    protected string $token;
    protected array $chatIds;

    public function __construct()
    {
        $this->token   = config('services.telegram.bot_token', '');

        // Parse comma-separated chat IDs → array
        $raw = config('services.telegram.chat_id', '');
        $this->chatIds = array_filter(
            array_map('trim', explode(',', $raw))
        );
    }

    public function sendOrderNotification($order, string $paymentMethod, $paymentInfo = null, float $receivedUSD = 0, float $changeUSD = 0): void
    {
        if (empty($this->token) || empty($this->chatIds)) {
            Log::warning('Telegram not configured — skipping notification.');
            return;
        }

        // រៀបចំបញ្ជីមុខទំនិញ
        $items = $order->orderItems->map(fn($i) =>
            "  • {$i->product->name} x{$i->qty} = \$" . number_format($i->total, 2)
        )->join("\n");

        // បង្កើត Message
        $message = "🧾 *វិក្កយបត្រថ្មី!*\n"
            . "━━━━━━━━━━━━━━━━\n"
            . "📋 Invoice: `#{$order->invoice_no}`\n"
            . "🗓 Date: " . date('d-M-Y H:i', strtotime($order->order_date)) . "\n"
            . "🪑 Table: " . ($order->table_id ?: 'N/A') . "\n"
            . "👤 Customer: " . ($order->customer->name ?? 'Walk-In') . "\n"
            . "👨‍💼 Cashier: " . (Auth::user()->name ?? 'Admin') . "\n"
            . "━━━━━━━━━━━━━━━━\n"
            . "🛒 *Items:*\n{$items}\n"
            . "━━━━━━━━━━━━━━━━\n"
            . "💵 Sub-Total: \$" . number_format($order->sub_total, 2) . "\n"
            . ($order->discount > 0 ? "🏷 Discount: -\$" . number_format($order->discount, 2) . "\n" : '')
            . "✅ *Grand Total: \$" . number_format($order->grand_total, 2) . "*\n"
            . "━━━━━━━━━━━━━━━━\n"
            . "💳 Payment: *{$paymentMethod}*\n";

        // បន្ថែមព័ត៌មានលម្អិតបើបង់តាមធនាគារ (Optional)
        if ($paymentInfo && $paymentInfo->bank) {
            $message .= "🏦 Bank: *{$paymentInfo->bank->bank_name}*\n";
        }

        $message .= "💰 Received: *\$" . number_format($receivedUSD, 2) . "*\n"
            . "🔄 Change: *\$" . number_format($changeUSD, 2) . "*";

        // ផ្ញើទៅកាន់គ្រប់ Chat ID
        foreach ($this->chatIds as $chatId) {
            try {
                Http::post("https://api.telegram.org/bot{$this->token}/sendMessage", [
                    'chat_id'    => $chatId,
                    'text'       => $message,
                    'parse_mode' => 'Markdown', // ឬ 'HTML' បើបងចង់ប្រើ Tag ផ្សេង
                ]);
            } catch (\Exception $e) {
                Log::error("Telegram failed for chat {$chatId}: " . $e->getMessage());
            }
        }
    }
}
