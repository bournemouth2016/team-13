package com.example.altay.bringoweb;

import android.app.Activity;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Build;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.annotation.RequiresApi;
import android.support.design.widget.FloatingActionButton;
import android.support.design.widget.Snackbar;
import android.support.v4.app.ActivityCompat;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.view.View;
import android.widget.Button;

import java.math.BigInteger;
import java.security.*;

import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.api.GoogleApiClient;
import com.google.android.gms.location.LocationRequest;
import com.google.android.gms.location.LocationServices;
import com.google.firebase.iid.FirebaseInstanceId;

import java.io.IOException;
import java.util.Date;
import java.util.Random;

import okhttp3.FormBody;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.RequestBody;

public class EmergencyActivity extends Activity {
    private boolean thread_running = true;
    private Button btn_emergency;
    private String token;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_emergency);

        btn_emergency = (Button) findViewById(R.id.btn_emergency);
        token = FirebaseInstanceId.getInstance().getToken();


        btn_emergency.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                Thread t = new Thread(new Runnable() {
                    @Override
                    public void run() {

                        while (thread_running) {

                            if (token != null) {

                                try {
                                OkHttpClient client = new OkHttpClient();
                                RequestBody body = new FormBody.Builder()
                                        .add("token", token)
                                        .add("type", "alert")
                                        .add("ident", getHashedMessage(String.valueOf(new Random().nextInt(5))))
                                        .add("status","new")
                                        .build();

                                Request request = new Request.Builder()
                                        .url("http://smartsail.esy.es/app/api.php")
                                        .post(body)
                                        .build();

                                    client.newCall(request).execute();
                                } catch (Exception e) {
                                    e.printStackTrace();
                                }

                                thread_running = false;

                                Intent i = new Intent(getApplicationContext(), EmergencyActivity.class);
                                startActivity(i);

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


    public String getHashedMessage(String s) throws NoSuchAlgorithmException{

        String plaintext = s;
        MessageDigest m = MessageDigest.getInstance("MD5");
        m.reset();
        m.update(plaintext.getBytes());
        byte[] digest = m.digest();
        BigInteger bigInt = new BigInteger(1,digest);
        String hashtext = bigInt.toString(16);
// Now we need to zero pad it if you actually want the full 32 chars.
        while(hashtext.length() < 32 ){
            hashtext = "0"+hashtext;
        }

        return hashtext;
    }

}
