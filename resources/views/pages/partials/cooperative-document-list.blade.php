@if($documents->isNotEmpty())
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($documents as $document)
    <div class="bg-etec-main p-4 rounded-xl shadow-sm border border-etec-dark/30 dark:border-white/10 flex items-start gap-4 hover:border-etec-accent transition group">
        <div class="w-10 h-10 bg-white/10 text-etec-accent rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-etec-accent/30 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <div class="flex-grow min-w-0">
            <h4 class="font-bold text-white text-sm mb-1 leading-tight">{{ $document->title }}</h4>
            <div class="flex items-center gap-2 text-xs text-green-200/70 mb-2">
                @if($document->period)
                <span>{{ $document->period }}</span>
                @endif
                @if($document->published_at)
                <span>{{ $document->published_at->format('d/m/Y') }}</span>
                @endif
            </div>
            @if($document->file_path)
            <a href="{{ photo_url($document->file_path) }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-bold text-etec-accent hover:text-white hover:underline">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Baixar Arquivo
            </a>
            @elseif($document->url)
            <a href="{{ $document->url }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-bold text-etec-accent hover:text-white hover:underline">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Acessar
            </a>
            @endif
        </div>
    </div>
    @endforeach
</div>
@else
<div class="bg-white/50 dark:bg-white/5 rounded-xl p-8 text-center border border-dashed border-gray-200 dark:border-white/10">
    <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $emptyMessage }}</p>
</div>
@endif
