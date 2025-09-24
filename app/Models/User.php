<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasOne; // <-- Importar HasOne\


/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Collection|Alumno[] $alumnos
 * @property Collection|Mensaje[] $mensajes
 * @property Collection|Notificacione[] $notificaciones
 * @property Collection|Profesore[] $profesores
 * @property Collection|Respaldo[] $respaldos
 *
 * @package App\Models
 */
class User extends Authenticatable
{
	use Notifiable, HasRoles, HasFactory; // ← Agregar HasRoles aquí

	protected $table = 'users';
	
	protected $casts = [
		'email_verified_at' => 'datetime'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'email',
		'password',
		'telefono',
		'estatus' 
	];
	
	// Función para generar una contraseña segura
	public static function generatePassword($length = 12)
	{
		$uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$lowercase = 'abcdefghijklmnopqrstuvwxyz';
		$numbers = '0123456789';
		$special = '!@#$%^&*()_+-=[]{}|;:,.<>?';
		
		$all = $uppercase . $lowercase . $numbers . $special;
		$password = '';
		
		// Asegurar al menos un carácter de cada tipo
		$password .= $uppercase[rand(0, strlen($uppercase) - 1)];
		$password .= $lowercase[rand(0, strlen($lowercase) - 1)];
		$password .= $numbers[rand(0, strlen($numbers) - 1)];
		$password .= $special[rand(0, strlen($special) - 1)];
		
		// Completar el resto de la longitud
		for ($i = 4; $i < $length; $i++) {
			$password .= $all[rand(0, strlen($all) - 1)];
		}
		
		// Mezclar los caracteres
		return str_shuffle($password);
	}
		

	public function alumno(): HasOne
	{
		return $this->hasOne(Alumno::class);
	}

	public function mensajes()
	{
		return $this->hasMany(Mensaje::class, 'usuario_id');
	}

	public function notificaciones()
	{
		return $this->hasMany(Notificacione::class, 'usuario_id');
	}

	public function profesor(): HasOne
	{
		return $this->hasOne(Profesor::class);
	}

	public function respaldos()
	{
		return $this->hasMany(Respaldo::class, 'usuario_id');
	}
}
