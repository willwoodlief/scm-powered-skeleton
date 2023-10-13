@php
    /**
     * @var \App\Models\Project $project
     */
@endphp

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive active-projects">
            <div class="tbl-caption">
                <h4 class="heading mb-0">Transactions</h4>
            </div>

            @php
                $extra_headers = \TorMorten\Eventy\Facades\Eventy::filter(\App\Plugins\Plugin::FILTER_PROJECT_TRANSACTION_TABLE_HEADERS, [])
            @endphp

            <table id="projects-tbl" class="table">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Business</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Employee</th>
                    @foreach($extra_headers as $header_key=> $header_html)
                        <th data-extra_header="{{$header_key}}">{{$header_html}}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>

                @foreach($project->project_expenses as  $transaction)

                    <tr>
                        <td>{{\App\Helpers\Utilities::formatDate($transaction->date)}}</td>
                        <td>{{$transaction->transaction_type}}</td>
                        <td>{{$transaction->business}}</td>
                        <td>{{$transaction->description}}</td>
                        <td>{{\App\Helpers\Utilities::formatMoney($transaction->amount)}}</td>
                        <td>{{$transaction->employee_id}}</td>
                        @foreach($extra_headers as $header_key=> $header_html)
                            <td data-extra_header="{{$header_key}}">
                                @filter(\App\Plugins\Plugin::FILTER_PROJECT_TRANSACTION_TABLE_COLUMN,'',$header_key,$transaction)
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>
</div>
