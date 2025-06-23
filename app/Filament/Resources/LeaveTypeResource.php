<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveTypeResource\Pages;
use App\Filament\Resources\LeaveTypeResource\RelationManagers;
use App\Models\LeaveType;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeaveTypeResource extends Resource
{
        protected static ?string $model = LeaveType::class;

        protected static ?string $navigationGroup = 'Administrator Tools';

        protected static ?string $navigationIcon = 'heroicon-o-swatch';

        public static function form(Form $form): Form
        {
                return $form
                        ->schema([
                                TextInput::make('name')
                                        ->label('Leave Type')
                                        ->required(),
                                TextInput::make('numberOfDays')
                                        ->integer(),
                                Forms\Components\MarkdownEditor::make('leaveRules')
                                        ->label('Leave Rules'),
                                Checkbox::make('weekendsInclusive'),
                        ]);
        }

        public static function table(Table $table): Table
        {
                return $table
                        ->columns([
                                TextColumn::make('id')
                                        ->sortable(),
                                TextColumn::make('name')
                                        ->label('Leave Type')
                                        ->searchable(),
                                TextColumn::make('numberOfDays')
                                        ->searchable(),
                                TextColumn::make('weekendsInclusive')
                                        ->badge()
                                        ->sortable()
                                        ->getStateUsing(function (LeaveType $value) {
                                                return $value->weekendsInclusive ? 'Yes' : 'No';
                                        })
                                        ->color(fn($state): string => match ($state) {
                                                'No' => 'info',
                                                'Yes' => 'warning',
                                        })
                                        ->searchable(),
                                TextColumn::make('leaveRules')
                                        ->wrap()
                                        ->label('Leave Rules')
                        ])
                        ->filters([
                                //
                        ])
                        ->actions([
                                Tables\Actions\EditAction::make(),
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
                        'index' => Pages\ListLeaveTypes::route('/'),
                        'create' => Pages\CreateLeaveType::route('/create'),
                        'edit' => Pages\EditLeaveType::route('/{record}/edit'),
                ];
        }
}
