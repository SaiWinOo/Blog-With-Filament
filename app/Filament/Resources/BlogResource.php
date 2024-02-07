<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Filament\Resources\BlogResource\RelationManagers;
use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Livewire\GlobalSearch;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('title')
                        ->required(),
                    TextInput::make('slug')
                        ->required(),
                    Select::make('category')
                        ->multiple()
                        ->preload()
                        ->relationship('categories', 'name'),
                    TextInput::make('reading_time')
                        ->required()
                        ->numeric(),
                    FileUpload::make('thumbnail')
                        ->required(),
                    RichEditor::make('content')
                        ->required()
                        ->columnSpanFull(),
                    Toggle::make('published')
                        ->required()->columnSpanFull()
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail'),
                TextColumn::make('title')->searchable(),
                CheckboxColumn::make('published'),
                TextColumn::make('categories.name'),
                Tables\Columns\TextColumn::make('reading_time')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('Filter by category')
                    ->relationship(name: 'categories', titleAttribute: 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            // 'view' => Pages\ViewBlog::route('/{record}'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
