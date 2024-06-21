<table class="table table-bordered text-center w-100" id="printDiv">
    <thead>
        <tr>
            <th rowspan="2">Date</th>
            <th rowspan="2">Rest</th>
            <th rowspan="2">TT</th>
            <!-- Duty on(Do)/ Travel Time (TT) 30 min before -->

            <!-- duty on 11.50 - 15 50 -->

            <!-- week start after 36 hr rest -->
            <th rowspan="2">STD</th>
            <th rowspan="2">ATD</th>
            <th rowspan="2">STA</th>
            <th rowspan="2">ATA</th>
            <th rowspan="2">On Duty</th>
            <th rowspan="2">Off Duty</th>
            <th colspan="2">DP</th>
            <th colspan="2">FDP</th>
            <th colspan="5">FT</th>
            <th colspan="2">Landings</th>
            <th rowspan="2">Break</th>
            <th rowspan="2">Ext. FDP</th>
        </tr>
        <tr>
            <th>Sector</th>
            <th>24 Hours</th>
            <th>Sector</th>
            <th>24 Hours</th>
            <th>Sector</th>
            <th>24 Hours</th>
            <th>7 Days</th>
            <th>30 Days</th>
            <th>365 Days</th>
            <th>Sector</th>
            <th>24 Hours</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $key => $value)
        <tr>
            <td>{{date('M d, Y',strtotime($value->date))}}</td>
            <td>{{get_Rest($user_id,$value->date,$value->id)}}</td>
            <td>{{get_do_tt($user_id,$value->date)}}</td>
            <td colspan="4" class="p-0 m-0">{!!get_STD_ATD_STA_ATA($user_id,$value->date)!!}</td>
            <td>{{get_on_duty($user_id,$value->date)}}</td>
            <td>{{get_off_duty($user_id,$value->date)}}</td>
            <td>{{get_DP_Sector($user_id,$value->date)}}</td>
            <td>{{get_DP_24Hours($user_id,$value->date)}}</td>
            <td>{{get_FDP_Sector($user_id,$value->date)}}</td>
            <td>{{get_FDP_24Hours($user_id,$value->date)}}</td>
            <td>{{get_FT_Sector($user_id,$value->date)}}</td>
            <td>{{get_FT_24Hours($user_id,$value->date)}}</td>
            <td>{{get_FT_IN_Days($user_id,$value->date,7)}}</td>
            <td>{{get_FT_IN_Days($user_id,$value->date,30)}}</td>
            <td>{{get_FT_IN_Days($user_id,$value->date,365)}}</td>
            <td>{{get_Landings_Sector($user_id,$value->date)}}</td>
            <td>{{get_Landings_24Hours($user_id,$value->date)}}</td>
            <td>{{get_Break($user_id,$value->date)}}</td>
            <td></td>
        </tr>
        @endforeach
    </tbody>
</table>