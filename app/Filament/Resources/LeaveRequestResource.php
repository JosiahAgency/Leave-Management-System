<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveRequestResource\Pages;
use App\Filament\Resources\LeaveRequestResource\RelationManagers;
use App\Models\LeaveRequest;
use App\Models\User;
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
                Fieldset::make('Basic Details')
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
                        Select::make('leaveTypeID')
                            ->preload()
                            ->required()
                            ->searchable()
                            ->relationship('leaveType', 'name')
                            ->live(),
                        Select::make('status')
                            ->options([
                                'Pending' => 'Pending',
                                'Denied' => 'Denied',
                                'Granted' => 'Granted',
                            ])
                            ->hiddenOn('create')
                            ->default('Pending')
                    ])->columns(3),
                Fieldset::make('Dates')
                    ->schema([
                        DatePicker::make('startDate')
                            ->required()
                            ->minDate(now())
                            ->weekStartsOnSunday()
                            ->native(false),
                        DatePicker::make('endDate')
                            ->required()
                            ->minDate(now())
                            ->weekStartsOnSunday()
                            ->native(false),
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
                    Action::make('changeStatus')
                        ->icon('heroicon-m-adjustments-horizontal')
                        ->form([
                            Select::make('status')
                                ->label('New Status')
                                ->options([
                                    'Pending' => 'Pending',
                                    'Granted' => 'Granted',
                                    'Denied' => 'Denied',
                                ])
                                ->required(),
                        ])
                        ->action(function (array $data, LeaveRequest $record): void {
                            $record->update([
                                'status' => $data['status'],
                            ]);
                        })
                        ->color('warning')
                        ->visible(fn(LeaveRequest $record) => true),
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
