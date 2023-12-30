<div class="row row-cards">
    <div class="col-sm-6 col-md-3">
        <div class="mb-3">
            <label class="form-label">Field</label>
            <select class="form-control form-select" id="filter-field">
                <option value="">All</option>
                @foreach($columns as $column)
                    @if($column['search'])
                        <option value="{{$column['field']}}">{{$column['title']}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="mb-3">
            <label class="form-label">Type</label>
            <select class="form-control form-select" id="filter-type">
                <option value="=">=</option>
                <option value="<"><</option>
                <option value="<="><=</option>
                <option value=">">></option>
                <option value=">=">>=</option>
                <option value="!=">!=</option>
                <option value="like">like</option>
            </select>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="mb-3">
            <label class="form-label">Value</label>
            <input type="test" id="filter-value" class="form-control" placeholder="Search here">
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="mb-3">
            <label class="form-label">Reset</label>
            <button class="btn btn-warning" id="filter-clear">Clear Filter</button>
        </div>
    </div>
</div>

<div class="mb-3">
    @foreach($export_types as $export)
        <button class="btn btn-md" id="download-{{$export}}"><i class="ti ti-file-type-{{$export}}"></i></button>
    @endforeach
</div>

<div id="{{$id}}">

</div>

@push('js')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function () {

            let headerMenu = function () {
                let customMenu = [];
                let columns = this.getColumns();

                for (let column of columns) {

                    //create checkbox element using font awesome icons
                    let icon = document.createElement("i");
                    icon.classList.add("ti");
                    icon.classList.add(column.isVisible() ? "ti-square-check" : "ti-square");

                    //build label
                    let label = document.createElement("span");
                    let title = document.createElement("span");

                    title.textContent = " " + column.getDefinition().title;

                    label.appendChild(icon);
                    label.appendChild(title);

                    //create menu item
                    customMenu.push({
                        label: label,
                        action: function (e) {
                            //prevent menu closing
                            e.stopPropagation();

                            //toggle current column visibility
                            column.toggle();

                            //change menu item icon
                            if (column.isVisible()) {
                                icon.classList.remove("ti-square");
                                icon.classList.add("ti-square-check");
                            } else {
                                icon.classList.remove("ti-square-check");
                                icon.classList.add("ti-square");
                            }
                        }
                    });
                }

                return customMenu;
            };

            let cols = @json($columns);
            cols.forEach(col => {
                col.headerMenu = headerMenu; // Assign the headerMenu function to each column
            });
            console.log(cols);
            let table_{{$id}} = new Tabulator("#{{$id}}", {
                paginationSizeSelector: [10, 50, 100, 500, 1000, 5000],
                paginationSize:@json($rows['limit']),
                sortMode: "remote",
                filterMode: "remote",
                layout: "fitDataFill",
                pagination: true, //enable pagination
                paginationMode: "remote", //enable remote pagination
                autoColumns: true,
                autoColumnsDefinitions: cols,
                paginationInitialPage: 1,
                paginationButtonCount: 5,
                ajaxURL: "{{$baseUrl}}",
                paginationCounter: "pages", //add pagination page counter
                dataSendParams: {
                    "page": "page", //change page request parameter to "pageNo"
                    "size": "limit", //change size request parameter to "limit"
                },
                dataReceiveParams: {
                    "last_page": "total_rows",
                },
                ajaxResponse: function (url, params, response) {
                    console.log(response);
                    //url - the URL of the request
                    //params - the parameters passed with the request
                    //response - the JSON object returned in the body of the response.
                    return response; //return the tableData property of a response json object
                },
            });

            //trigger download of data.csv file
            @if(in_array('csv', $export_types))
            document.getElementById("download-csv").addEventListener("click", function () {
                const filename = generateFilename("{{ class_basename($table) }}", "csv");
                table_{{$id}}.download("csv", filename);
            });
            @endif

            //trigger download of data.xlsx file
            @if(in_array('xls', $export_types))
            document.getElementById("download-xls").addEventListener("click", function () {
                const filename = generateFilename("{{ class_basename($table) }}", "xlsx");
                table_{{$id}}.download("xlsx", filename, {sheetName: '{{ class_basename($table) }}'});
            });
            @endif
            //trigger download of data.pdf file
            @if(in_array('pdf', $export_types))
            document.getElementById("download-pdf").addEventListener("click", function () {
                const filename = generateFilename("{{ class_basename($table) }}", "pdf");
                table_{{$id}}.download("pdf", filename, {
                    orientation: "portrait", //set page orientation to portrait
                    title: "{{ class_basename($table) }}", //add title to report
                });
            });
            @endif
            //trigger download of data.html file
            @if(in_array('html', $export_types))
            document.getElementById("download-html").addEventListener("click", function () {
                const filename = generateFilename("{{ class_basename($table) }}", "html");
                table_{{$id}}.download("html", filename, {style: true});
            });
            @endif
            // Define variables for input elements
            const fieldEl = document.getElementById("filter-field");
            const typeEl = document.getElementById("filter-type");
            const valueEl = document.getElementById("filter-value");
            const clearBtn = document.getElementById("filter-clear");

            // Trigger setFilter function with correct parameters
            function updateFilter() {
                const filterVal = fieldEl.value;
                const typeVal = typeEl.value;
                if (filterVal || typeVal) {
                    table_{{$id}}.setFilter(filterVal, typeVal, valueEl.value);
                }
            }

            // Update filters on value change
            fieldEl.addEventListener("keyup", updateFilter);
            typeEl.addEventListener("change", updateFilter);
            valueEl.addEventListener("keyup", updateFilter);

            // Clear filters on "Clear Filters" button click
            clearBtn.addEventListener("click", function () {
                fieldEl.value = "";
                typeEl.value = "=";
                valueEl.value = "";
                table_{{$id}}.clearFilter();
            });

        });
    </script>
@endpush
