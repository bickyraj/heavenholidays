<?php
  
namespace App\Exports;
  
use App\EmailSubscriber;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SubscriberExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
	public static $n = 1;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return EmailSubscriber::select('id', 'name', 'email', 'created_at')->get();
    }

    /**
    * @var Invoice $subscriber
    */
    public function map($subscriber): array
    {
        return [
        	self::$n++,
            $subscriber->name,
            $subscriber->email,
            formatDate($subscriber->created_at),
        ];
    }

    public function headings(): array
    {
        return [
            'S.N',
            'Name',
            'Email',
            'Created At'
        ];
    }
}