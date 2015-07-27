<form method="POST" id="{{ $formId }}" action="{{ $formAction }}" enctype="multipart/form-data">
    {!! csrf_field() !!}
    @if (isset($formMethod))
        <input type="hidden" name="_method" value="{{ $formMethod }}" />
    @endif
    <div class="form-group">
        <label for="item_name">Item Name:</label>
        <input type="text" id="item_name" name="item_name" class="form-control" placeholder="e.g. Chair"
               value="{{ old('item_name', isset($auction['title']) ? $auction['title'] : null)  }}"
               @if(isset($disabledInputs['item_name']) and $disabledInputs['item_name'] === true) disabled @endif />
    </div>
    <div class="form-group">
        <label for="description">Describe the item:</label>
        <textarea id="description" name="description" class="form-control" rows="4" placeholder=""
                  @if(isset($disabledInputs['description']) and $disabledInputs['description'] === true) disabled @endif
                >{{ old('description', isset($auction['description']) ? $auction['description'] : null)  }}</textarea>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="category">Item Category:</label>
                <select id="category" name="category" class="form-control"
                        @if(isset($disabledInputs['category']) and $disabledInputs['category'] === true) disabled @endif>
                    <option value="0" disabled>--- Select ---</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                                @if (isset($auction['category_id']) and $auction['category_id'] > 0 )
                                selected
                                @endif
                                >{{ $category->category }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="condition">Item Condition:</label>
                <select id="condition" name="condition" class="form-control"
                        @if(isset($disabledInputs['condition']) and $disabledInputs['condition'] === true) disabled @endif>
                    <option value="0" disabled>--- Select ---</option>
                    @foreach ($conditions as $condition)
                        <option value="{{ $condition->id }}"
                                @if (isset($auction['condition_id']) and $auction['condition_id'] > 0 )
                                selected
                                @endif
                                >{{ $condition->condition }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="starting_price">Starting price:</label>
                <input type="text" id="starting_price" name="starting_price" class="form-control"
                       placeholder="e.g. $1.00"
                       @if(isset($disabledInputs['starting_price']) and $disabledInputs['starting_price'] === true) disabled
                       @endif
                       value="{{ old('starting_price', isset($auction['start_price']) ? $auction['start_price'] :  "$0.00")  }}" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="days">Auction End Date (1 to 14 days)</label>
                <div class="input-group date" id="auctionFormDateEndingSelector">
                    <input id="date_ending" name="date_ending" type="text" class="form-control"
                           value="{{ old('date_ending', isset($auction['end_date']) ? $auction['end_date'] :  null)  }}"
                           @if(isset($auction['ending_today']) and $auction['ending_today'] === true) disabled @endif
                           @if(isset($disabledInputs['date_ending']) and $disabledInputs['date_ending'] === true) disabled @endif />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="photo">Upload a photo (max 1 MB) (optional):</label>
        <input type="file" id="photo" name="photo" accept="image/*" class="form-control" value="{{ old('photo') }}"
               @if(isset($disabledInputs['photo']) and $disabledInputs['photo'] === true) disabled @endif />
    </div>
    <button type="submit" class="btn btn-lg btn-block btn-primary">{{ $submitButtonText }}</button>
</form>
