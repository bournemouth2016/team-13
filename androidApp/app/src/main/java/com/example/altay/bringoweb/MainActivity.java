package com.example.altay.bringoweb;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.webkit.ConsoleMessage;
import android.webkit.GeolocationPermissions;
import android.webkit.WebChromeClient;
import android.webkit.WebSettings;
import android.webkit.WebView;

import com.google.firebase.iid.FirebaseInstanceId;
import com.google.firebase.messaging.FirebaseMessaging;

import java.io.IOException;
import java.net.InetAddress;
import java.net.NetworkInterface;
import java.util.Enumeration;

import okhttp3.FormBody;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.RequestBody;
import okhttp3.Response;

import android.Manifest;
import android.webkit.WebViewClient;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;




public class MainActivity extends AbsRuntimePermission {

    private static final String SERVER_DOMAIN = "bringoapp.com";
    private static final int REQUEST_PERMISSION = 10;
    private boolean thread_running = true;
    private String token;
    private String IPAddr;

    private Button login;
    private EditText name;
    private EditText telephone;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        this.setContentView(R.layout.activity_main);

        // Permission Check
        requestAppPermissions(new String[]{
                        Manifest.permission.ACCESS_FINE_LOCATION},
                R.string.app_name, REQUEST_PERMISSION);

        // Set IP
        IPAddr = getLocalIpAddress();

        //FirebaseMessaging.getInstance().subscribeToTopic("test");

        token = FirebaseInstanceId.getInstance().getToken();
        login = (Button) findViewById(R.id.btn_login);
        name = (EditText) findViewById(R.id.name);
        telephone = (EditText) findViewById(R.id.telephone);
        login.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Thread t = new Thread(new Runnable() {
                    @Override
                    public void run() {
                        while (thread_running) {

                            if (token != null) {
                                OkHttpClient client = new OkHttpClient();
                                RequestBody body = new FormBody.Builder()
                                        .add("token", token)
                                        .add("name",name.getText().toString())
                                        .add("phone",telephone.getText().toString())
                                        .add("type","register")
                                        .build();

                                Request request = new Request.Builder()
                                        .url("http://smartsail.esy.es/app/api.php")
                                        .post(body)
                                        .build();
                                try {
                                 Response response = client.newCall(request).execute();
                                    if(response.code() == 200){

                                        Intent i = new Intent(getApplicationContext(),EmergencyActivity.class);
                                        startActivity(i);

                                    }
                                } catch (IOException e) {
                                    e.printStackTrace();
                                }

                                thread_running = false;
                            } else {
                                System.out.println("- Token not retrieved -");
                            }
                            try {
                                Thread.sleep(1000);
                            } catch (InterruptedException e) {
                                e.printStackTrace();
                            }
                        }
                    }
                });
                t.start();

        }
        });

    }

    @Override
    public void onPermissionsGranted(int requestCode) {
        Toast.makeText(getApplicationContext(), "Bringo will access your location.", Toast.LENGTH_LONG).show();
    }

    public String getLocalIpAddress() {
        try {
            for (Enumeration<NetworkInterface> en = NetworkInterface.getNetworkInterfaces();
                 en.hasMoreElements(); ) {
                NetworkInterface intf = en.nextElement();
                for (Enumeration<InetAddress> enumIpAddr = intf.getInetAddresses(); enumIpAddr.hasMoreElements(); ) {
                    InetAddress inetAddress = enumIpAddr.nextElement();
                    if (!inetAddress.isLoopbackAddress()) {
                        return inetAddress.getHostAddress().toString();
                    }
                }
            }
        } catch (Exception ex) {
            Log.e("IP Address", ex.toString());
        }
        return null;
    }

}
