<?php

namespace app\src\model\repository;

use Exception;

class MailRepository
{
	public static function send_mail(array $email, string $subject, string $message): bool
	{
		if (count($email) === 0) return true;
		$headers = "MIME-Version: 1.0" . "\r\n"
			. "Content-type:text/html;charset=UTF-8" . "\r\n"
			. 'From: ' . SMTP_USERNAME . "\r\n";

		foreach ($email as $to) {
			if (is_null($to) || $to === "") continue;
			try {
				$smtpConn = fsockopen(SMTP_SERVER, SMTP_PORT, $errno, $errstr, 5);

				if (!$smtpConn) throw new Exception("SMTP Connection Failed: $errstr ($errno)");

				self::sendCommand($smtpConn, "EHLO example.com");
				$response = fgets($smtpConn, 512);

				if (str_contains($response, '250-STARTTLS')) {
					self::sendCommand($smtpConn, "STARTTLS");
					stream_socket_enable_crypto($smtpConn, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
				}

				self::sendCommand($smtpConn, "AUTH LOGIN");
				self::sendCommand($smtpConn, base64_encode(SMTP_USERNAME));
				self::sendCommand($smtpConn, base64_encode(SMTP_PASSWORD));
				self::sendCommand($smtpConn, "MAIL FROM:<" . SMTP_USERNAME . ">");
				self::sendCommand($smtpConn, "RCPT TO:<$to>");
				self::sendCommand($smtpConn, "DATA");
				self::sendCommand($smtpConn, "Subject: $subject");
				self::sendCommand($smtpConn, "To: $to");
				self::sendCommand($smtpConn, "$headers");
				self::sendCommand($smtpConn, "$message");
				self::sendCommand($smtpConn, ".");

				fputs($smtpConn, "QUIT\r\n");
				fclose($smtpConn);

			} catch
			(Exception) {
				return false;
			}
		}
		return true;
	}

	private
	static function sendCommand($smtpConn, string $command): void
	{
		fputs($smtpConn, $command . "\r\n");
		fgets($smtpConn, 512);
	}
}