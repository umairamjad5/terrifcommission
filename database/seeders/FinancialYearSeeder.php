<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FinancialYear;

class FinancialYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1;$i<11;$i++)
        {
            FinancialYear::insert([    
                'financial_year'  =>  date('Y',time()).' - '.date('Y',strtotime('+1 year')),        // for one year
                'financial_year'  =>  date('Y',strtotime('+'.$i.' year')).' - '.date('Y',strtotime('+'. $i+1 .' year')),        //  for one year above
            ]); 

        }
    }
}
