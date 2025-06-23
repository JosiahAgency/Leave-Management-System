<?php

namespace App\Filament\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use AlperenErsoy\FilamentExport\FilamentExport;
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
                                Forms\Components\Wizard::make([
                                        Forms\Components\Wizard\Step::make('User Details')
                                                ->icon('heroicon-o-user')
                                                ->completedIcon('heroicon-m-hand-thumb-up')
                                                ->schema([
                                                        Select::make('userID')
                                                                ->label('Staff Name')
                                                                ->live()
                                                                ->preload()
                                                                ->required()
                                                                ->searchable()
                                                                ->default(fn() => Auth::id())
                                                                ->disabled(fn() => Auth::guest() || Auth::user()->hasRole('user'))
                                                                ->relationship('user', 'name'),
                                                        Forms\Components\Hidden::make('departmentID')
                                                                ->label('Department')
                                                                ->default(function () {
                                                                        $user = Auth::user();
                                                                        return $user?->departmentID ?? null;
                                                                }),
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
                                                ])->columns(2),

                                        Forms\Components\Wizard\Step::make('Leave Details')
                                                ->icon('heroicon-o-newspaper')
                                                ->completedIcon('heroicon-m-hand-thumb-up')
                                                ->schema([
                                                        Fieldset::make('Duration')
                                                                ->schema([
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
                                                                                }),
                                                                        TextInput::make('weekendInclusive')
                                                                                ->disabled(),
                                                                        TextInput::make('numberOfDays')
                                                                                ->required()
                                                                                ->numeric()
                                                                                ->reactive()
                                                                                ->afterStateUpdated(function (Set $set, ?string $state, $get) {
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
                                                                ]),
                                                        Forms\Components\Hidden::make('status')
                                                                ->default('Pending'),
//                                                        Select::make('status')
//                                                                ->label('Request Status')
//                                                                ->options([
//                                                                        'Pending' => 'Pending',
//                                                                        'Denied' => 'Denied',
//                                                                        'Granted' => 'Granted',
//                                                                ])
//                                                                ->disabled()
//                                                                ->default('Pending'),
                                                        MarkdownEditor::make('reason'),
                                                ]),
                                ])->columnSpanFull(),
                        ]);
        }

        public static function table(Table $table): Table
        {
                return $table
                        ->columns([
                                TextColumn::make('user.name')->searchable(),
                                Textcolumn::make('department.name')
                                        ->searchable()
                                        ->sortable(),
                                TextColumn::make('leaveType.name')->searchable()->sortable(),
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
                                                'Denied' => 'danger',
                                                'Granted' => 'success'
                                        })
                                        ->searchable()
                                        ->sortable(),
                                TextColumn::make('hod_approval')
                                        ->badge()
                                        ->color(fn(string $state): string => match ($state) {
                                                'pending' => 'warning',
                                                'rejected' => 'danger',
                                                'approved' => 'success'
                                        })
                                        ->searchable()
                                        ->sortable(),
                                TextColumn::make('hr_approval')
                                        ->badge()
                                        ->color(fn(string $state): string => match ($state) {
                                                'pending' => 'warning',
                                                'rejected' => 'danger',
                                                'approved' => 'success'
                                        })
                                        ->searchable()
                                        ->sortable(),
                                TextColumn::make('hr_comment')->toggleable(isToggledHiddenByDefault: true),
                                TextColumn::make('hod_comment')->toggleable(isToggledHiddenByDefault: true),
                        ])->defaultSort('updated_at', 'desc')
                        ->filters([
                                //
                        ])
                        ->emptyStateIcon('heroicon-o-bookmark')
                        ->emptyStateDescription('Once you apply for leave your application/s shall be displayed here.')
                        ->emptyStateActions([
                                Tables\Actions\CreateAction::make()
                                        ->color('warning')
                                        ->icon('heroicon-o-arrow-down-on-square-stack')
                                        ->label('Apply for Leave'),
                        ])
                        ->actions([
                                ActionGroup::make([
                                        FilamentExportBulkAction::make('Export'),
                                        ViewAction::make(),
                                        EditAction::make(),
                                        DeleteAction::make(),
                                ])->iconButton()
                        ])
                        ->bulkActions([
                                Tables\Actions\BulkActionGroup::make([
                                        FilamentExportBulkAction::make('Export'),
                                        Tables\Actions\DeleteBulkAction::make(),
                                ]),
                        ]);
        }

        public static function getEloquentQuery(): Builder
        {
                $user = Auth::user();
                return parent::getEloquentQuery()->where('userID', $user->id);
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
                        'approvals' => Pages\Approvals::route('/approvals')
                ];
        }
}
