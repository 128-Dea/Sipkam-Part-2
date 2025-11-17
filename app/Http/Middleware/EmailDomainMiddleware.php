<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class EmailDomainMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $role = $request->input('role');
        $email = $request->input('email');

        if ($role && $email) {
            $validator = Validator::make($request->all(), [
                'email' => [
                    'required',
                    'email',
                    function ($attribute, $value, $fail) use ($role) {
                        if ($role === 'mahasiswa' && !str_ends_with($value, '@mhs.unesa.ac.id')) {
                            $fail('Email mahasiswa harus menggunakan domain @mhs.unesa.ac.id');
                        } elseif ($role === 'petugas' && !str_ends_with($value, '@admin.ac.id')) {
                            $fail('Email petugas harus menggunakan domain @admin.ac.id');
                        }
                    },
                ],
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        return $next($request);
    }
}
