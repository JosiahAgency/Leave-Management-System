<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveRequestResource\Pages;
use App\Filament\Resources\LeaveRequestResource\RelationManagers;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class LeaveRequestResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('User Details')
                    ->schema([
                        Select::make('userID')
                            ->label('Staff Name')
                            ->live()
                            ->preload()
                            ->required()
                            ->searchable()
                            ->default(fn() => Auth::id())
                            ->disabled(function () {
                                $user = Auth::user();
                                if ($user->job_title === 'admin') {
                                    return false;
                                }
                            })
                            ->relationship('user', 'name')
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                if ($state && $user = User::find($state)) {
                                    $set('supervisor', $user->supervisor);
                                }
                            }),
                        TextInput::make('supervisor')
                            ->default(function () {
                                $user = Auth::user();
                                return $user?->supervisor ?? null;
                            })
                            ->readOnly(),
                    ])->columns(2),

                Fieldset::make('Leave Details')
                    ->schema([
                        Select::make('leaveTypeID')
                            ->preload()
                            ->required()
                            ->searchable()
                            ->relationship('leaveType', 'name')
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                if ($state && $leaveType = LeaveType::find($state)) {
                                    $set('weekendInclusive', $leaveType->weekendsInclusive);
                                    $set('numberOfDays', $leaveType->numberOfDays);
                                }
                            }),
                        DatePicker::make('startDate')
                            ->required()
                            ->minDate(now())
                            ->weekStartsOnSunday()
                            ->reactive()
                            ->native(false)
                            ->afterStateUpdated(function (Set $set, ?string $state, $get) {
                                if ($state && $get('numberOfDays')) {
                                    $startDate = Carbon::parse($state);
                                    $numberOfDays = (int)$get('numberOfDays');
                                    $weekendInclusive = $get('weekendInclusive') === 'Yes';

                                    if ($weekendInclusive) {
                                        $endDate = $startDate->copy()->addDays($numberOfDays - 1)->toDateString();
                                    } else {
                                        $count = 0;
                                        $endDate = $startDate->copy();
                                        while ($count < $numberOfDays) {
                                            if (!$endDate->isWeekend()) {
                                                $count++;
                                            }
                                            if ($count < $numberOfDays) {
                                                $endDate->addDay();
                                            }
                                        }
                                        $endDate = $endDate->toDateString();
                                    }
                                    $set('endDate', $endDate);
                                }
                                //                                if ($get('weekendInclusive') == 1) {
//                                    if ($state && $get('numberOfDays') && $get('weekendInclusive')) {
//                                        $endDate = Carbon::parse($state)->addDays($get('numberOfDays') - 1)->toDateString();
//                                        $set('endDate', $endDate);
//                                    }
//                                } else {
//
//                                }
                            }),
                        TextInput::make('weekendInclusive')
                            ->disabled(),
                        TextInput::make('numberOfDays')
                            ->required()
                            ->numeric()
                            ->reactive()
                            ->disabled()
                            ->afterStateUpdated(function (Set $set, ?string $state, $get) {
//                                if ($state && $get('startDate')) {
//                                    $endDate = Carbon::parse($get('startDate'))->addDays($state - 1)->toDateString();
//                                    $set('endDate', $endDate);
//                                }
                                if ($state && $get('startDate')) {
                                    $startDate = Carbon::parse($get('startDate'));
                                    $numberOfDays = $state;

                                    if ($get('weekendInclusive') === 'Yes') {
                                        $endDate = $startDate->copy()->addDays($numberOfDays - 1)->toDateString();
                                        $set('endDate', $endDate);

                                    } else {
                                        $count = 0;
                                        $endDate = $startDate->copy();
                                        while ($count < $numberOfDays) {
                                            if (!$endDate->isWeekend()) {
                                                $count++;
                                            }
                                            if ($count < $numberOfDays) {
                                                $endDate->addDay();
                                            }
                                        }
                                        $endDate = $endDate->toDateString();
                                    }
                                    $set('endDate', $endDate);
                                }
                            }),
                        DatePicker::make('endDate')
                            ->required()
                            ->disabled()
                            ->dehydrated(),
                        Select::make('status')
                            ->label('Request Status')
                            ->options([
                                'Pending' => 'Pending',
                                'Denied' => 'Denied',
                                'Granted' => 'Granted',
                            ])
                            ->disabled()
                            ->default('Pending'),
//                            ->hiddenOn('create')
                    ]),
                Fieldset::make('Reason')
                    ->schema([
                        MarkdownEditor::make('reason')
                            ->label(''),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->searchable(),
                TextColumn::make('leaveType.name')->searchable()->sortable(),
                TextColumn::make('supervisor'),
                TextColumn::make('startDate')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('endDate')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'Denined' => 'danger',
                        'Granted' => 'success'
                    })
                    ->searchable()
                    ->sortable(),
            ])->defaultSort('updated_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
//                    Action::make('changeStatus')
//                        ->icon('heroicon-m-adjustments-horizontal')
//                        ->form([
//                            Select::make('status')
//                                ->label('New Status')
//                                ->options([
//                                    'Pending' => 'Pending',
//                                    'Granted' => 'Granted',
//                                    'Denied' => 'Denied',
//                                ])
//                                ->required(),
//                        ])
//                        ->action(function (array $data, LeaveRequest $record): void {
//                            $record->update([
//                                'status' => $data['status'],
//                            ]);
//                        })
//                        ->color('warning')
//                        ->visible(fn(LeaveRequest $record) => true),
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])->button()
                    ->label('Actions'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        return parent::getEloquentQuery()->where('id', $user->id);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeaveRequests::route('/'),
            'create' => Pages\CreateLeaveRequest::route('/create'),
            'edit' => Pages\EditLeaveRequest::route('/{record}/edit'),
        ];
    }
}
