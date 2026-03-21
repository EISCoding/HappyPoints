<?php
declare(strict_types=1);

function e(?string $value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function appName(): string { static $c=null; if($c===null){$c=require BASE_PATH.'/config/app.php';} return (string)($c['name'] ?? 'App'); }
function old(string $key, mixed $default=''): mixed { $old = Session::getFlash('_old_input', []); return $old[$key] ?? $default; }
function withOldInput(array $input): void { Session::flash('_old_input', $input); }
function flashMessage(string $type, string $message): void { Session::flash('message', ['type'=>$type,'text'=>$message]); }
function getFlashMessage(): ?array { $m=Session::getFlash('message'); return is_array($m)?$m:null; }
