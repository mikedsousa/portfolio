<?php
	require "Functions.php";
########### Classes e arquivos pra envio de email autenticado ###########
	require "PHPMailer/src/Exception.php";
	require "PHPMailer/src/PHPMailer.php";
	require "PHPMailer/src/SMTP.php";

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	$Rand_Email = mt_rand(10, 9999); // Gera um código randomico
	
	$EMAIL_DEBUG = false; // deixe em false caso não precise debugar os envios
	$RECEBER_COPIA = false; // deixe em true caso queria receber uma cópia do email que o receber está recebendo
	$ENCRIPTACAO = true;
	
	VerificaEnvioInfo(); // Verifica se houve um post (não aceita envios via get ou outra forma)
	
	$Status_Recaptcha = verifyResponse($_POST['g-recaptcha-response']);
	
	
	if (isset($Status_Recaptcha) && $Status_Recaptcha['success']) {
	
	$Nome_Form = getPost('nome');
	
	$Email_Form = getPost('email');
	
	$Assunto_Form = getPost('assunto');
	
	$Mensagem_Form = getPost('mensagem');
	
	$portifolio_Form = getPost('portifolio');
	
	if(empty($Email_Form) || filter_var($Email_Form, FILTER_VALIDATE_EMAIL) === false){  
        echo "<script>alert('E-mail não parece ser valido!'); </script>";

		die("<script> close(); </script>"); 
    }  
	
	if(empty($Nome_Form)){  
         echo "<script>alert('Nome não foi informado!'); </script>";

		die("<script> close(); </script>"); 
    }  
	
	if(empty($Assunto_Form)){  
         echo "<script>alert('Assunto não foi informado!'); </script>";

		die("<script> close(); </script>"); 
    } 
	
	if(empty($Mensagem_Form)){  
         echo "<script>alert('Mensagem não foi informado!'); </script>";

		die("<script> close(); </script>"); 
    } 
	
	
	$MensagemHTML = "
	
	<table border='0' width='100%' cellspacing='0' cellpadding='0'>
	<tr>
		<td><font face='Arial' size='4'>Nome do Remetente</font></td>
		<td><b><font face='Arial' size='4'>$Nome_Form</font></b></td>
	</tr>
	<tr>
		<td><font face='Arial' size='4'>E-mail do Remetente</font></td>
		<td><b><font face='Arial' size='4'>$Email_Form</font></b></td>
	</tr>
	<tr>
		<td><font face='Arial' size='4'>Assunto</font></td>
		<td><b><font face='Arial' size='4'>$Assunto_Form</font></b></td>
	</tr>
	<tr>
		<td><font face='Arial' size='4'>Mensagem</font></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td colspan='2'>
		<p align='center'><b><font face='Arial' size='4'>$Mensagem_Form</font></b></td>
	</tr>
	<tr>
		<td colspan='2'>
		&nbsp;</td>
	</tr>
	<tr>
		<td colspan='2'>
		&nbsp;</td>
	</tr>
	<tr>
		<td colspan='2'>
		<p align='center'><font color='#FFFFFF'>$Rand_Email</font></td>
	</tr>

</table>
	
	
	";
	
	
	$MensagemTXT = "
		
		\r\n\r\n
		Nome do Remetente: $Nome_Form
		\r\n
		E-mail do Remetente: $Email_Form
		\r\n
		Assunto: $Assunto_Form
		\r\n\r\n
		
		Mensagem: $Mensagem_Form
		\r\n\r\n\r\n
		$Rand_Email
		
		";
	
	
	if($portifolio_Form == 'portifolio'){
	




	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->setLanguage('pt-br', "PHPMailer\language");
	$mail->CharSet = 'UTF-8';
	if ($EMAIL_DEBUG) {
		$mail->SMTPDebug = SMTP::DEBUG_SERVER;
	}
	$mail->Host = 'smtp.gmail.com';
	$mail->SMTPAuth = true;
	$mail->Debugoutput = 'html';
	$mail->SMTPDebug = 3;
	$mail->Username = 'mikedesousa92@gmail.com';
	$mail->Password = '03onefortheroad';
	if ($ENCRIPTACAO) {
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
	}
	$mail->Port = '587';
	$mail->isHTML(true);
	$mail->Subject = "$Nome_Form enviou um e-mail com o assunto: $Assunto_Form [$Rand_Email]"; // Assunto do E-mail

	$mail->setFrom('contato@mikedesousa.site', 'Mike de Sousa');
	$mail->addReplyTo('EMAIL_REMETENTE@domininio.com', 'NOME_REMETENTE'); // Responder para
	if ($RECEBER_COPIA) {
		$mail->addBCC('EMAIL_COPIA@domininio.com'); // Cópia Oculta
	}

	$mail->addAddress($Email_Form, $Nome_Form); // Destinatário

	$mail->AltBody = $MensagemTXT;
	$mail->Body = $MensagemHTML;
	
	$mail->Debugoutput = function ($str, $level) {
		$Data_log = date('d-m-Y');
		$Rand_Mail_Log = mt_rand(10, 999);
		$NomeLog = "smtp_send-" . $Data_log . ".log";
		file_put_contents("$NomeLog", date('d/m/Y H:i:s') . "\t $level \t $str \n", FILE_APPEND | LOCK_EX);
	};

	if ($mail->send()) {

		echo "<script>alert('Mensagem enviada com sucesso!'); </script>";

		die("<script> close(); </script>");

		//Limpa os destinatários e os anexos
		$mail->ClearAllRecipients();
		$mail->ClearAttachments();

	} else {
		
		$Erro_Envio_Email = $mail->ErrorInfo;

		echo "<script>alert('Ocorreu um erro ao enviar seu email!'); </script>";

		die("$Erro_Envio_Email");

	}

	}else{
		//se houver erro, alerta em tela e retorna ao formulário
		echo '<script>alert("Parece que sua requisição não foi feita do site de origem que deveria ser, verifique e tente novamente.")</script>';
		
		die("<script> close(); </script>");
	}

	}else{
		
		//se houver erro, alerta em tela e retorna ao formulário
		echo '<script>alert("Falha na resposta do Google recaptcha, favor tentar novamente.")</script>';
		
		die("<script> close(); </script>");
} 



?>